<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WorkoutController extends Controller
{
    /**
     * Tipe workout yang diizinkan.
     */
    private const VALID_WORKOUT_TYPES = [
        'gym',
        'cardio',
        'calisthenic',
    ];

    /**
     * Menampilkan halaman detail workout.
     */
    public function show(string $type): View
    {
        abort_unless(
            in_array(
                $type,
                self::VALID_WORKOUT_TYPES,
                true
            ),
            404
        );

        $user = Auth::user();

        abort_if(! $user, 401);

        $profile = Profile::where(
            'user_id',
            $user->id
        )->first();

        $profileCompleted = $profile
            && $profile->isCompleteFor($user);

        return view(
            'workout.' . $type,
            compact('profileCompleted')
        );
    }

    /**
     * Membuat sesi workout baru.
     *
     * Permintaan ganda dalam waktu sangat singkat akan memakai
     * sesi yang sama, selama sesi tersebut belum memiliki progress.
     */
    public function startSession(
        Request $request
    ): JsonResponse {
        $userId = Auth::id();

        if (! $userId) {
            return $this->errorResponse(
                'Silakan login terlebih dahulu.',
                401
            );
        }

        $validated = $request->validate(
            [
                'program_id' => [
                    'required',
                    'integer',
                    'exists:programs,id',
                ],
            ],
            [
                'program_id.required' =>
                    'Program wajib dipilih.',

                'program_id.integer' =>
                    'Program tidak valid.',

                'program_id.exists' =>
                    'Program tidak ditemukan.',
            ]
        );

        if (
            ! Schema::hasTable('workout_sessions')
        ) {
            return $this->errorResponse(
                'Tabel workout session belum tersedia.',
                500
            );
        }

        $programId =
            (int) $validated['program_id'];

        try {
            $result = DB::transaction(
                function () use (
                    $userId,
                    $programId
                ): array {
                    /*
                     * Mengunci baris user agar dua request start
                     * bersamaan tidak membuat sesi ganda.
                     */
                    DB::table('users')
                        ->where('id', $userId)
                        ->lockForUpdate()
                        ->first();

                    /*
                     * Cari sesi yang baru dibuat maksimal 30 detik
                     * sebelumnya dan belum mempunyai progress.
                     *
                     * Ini hanya untuk menangani request ganda,
                     * bukan melanjutkan workout lama.
                     */
                    $existingQuery =
                        DB::table('workout_sessions')
                            ->where(
                                'user_id',
                                $userId
                            )
                            ->where(
                                'program_id',
                                $programId
                            )
                            ->whereNull(
                                'duration_minutes'
                            )
                            ->where(
                                'session_date',
                                '>=',
                                now()->subSeconds(30)
                            );

                    if (
                        Schema::hasTable(
                            'workout_progress'
                        )
                    ) {
                        $existingQuery->whereNotExists(
                            function ($query): void {
                                $query
                                    ->select(
                                        DB::raw(1)
                                    )
                                    ->from(
                                        'workout_progress'
                                    )
                                    ->whereColumn(
                                        'workout_progress.session_id',
                                        'workout_sessions.id'
                                    );
                            }
                        );
                    }

                    $existingSession =
                        $existingQuery
                            ->orderByDesc('id')
                            ->first();

                    if ($existingSession) {
                        return [
                            'session_id' =>
                                (int) $existingSession->id,

                            'reused' => true,
                        ];
                    }

                    $sessionData = [
                        'user_id' => $userId,
                        'program_id' => $programId,
                        'session_date' => now(),
                        'duration_minutes' => null,
                    ];

                    if (
                        Schema::hasColumn(
                            'workout_sessions',
                            'created_at'
                        )
                    ) {
                        $sessionData['created_at'] =
                            now();
                    }

                    if (
                        Schema::hasColumn(
                            'workout_sessions',
                            'updated_at'
                        )
                    ) {
                        $sessionData['updated_at'] =
                            now();
                    }

                    $sessionId =
                        DB::table(
                            'workout_sessions'
                        )->insertGetId(
                            $sessionData
                        );

                    return [
                        'session_id' =>
                            (int) $sessionId,

                        'reused' => false,
                    ];
                }
            );

            return response()->json([
                'success' => true,

                'message' => $result['reused']
                    ? 'Sesi workout dilanjutkan.'
                    : 'Sesi workout berhasil dimulai.',

                'session_id' =>
                    $result['session_id'],

                'program_id' =>
                    $programId,

                'reused' =>
                    $result['reused'],
            ]);
        } catch (\Throwable $error) {
            report($error);

            return $this->errorResponse(
                'Gagal memulai sesi workout.',
                500
            );
        }
    }

    /**
     * Menyimpan exercise yang sudah diselesaikan.
     *
     * Frontend dapat mengirim exercise_id atau exercise_name.
     * Penggunaan exercise_id lebih disarankan.
     */
    public function saveProgress(
        Request $request
    ): JsonResponse {
        $userId = Auth::id();

        if (! $userId) {
            return $this->errorResponse(
                'Silakan login terlebih dahulu.',
                401
            );
        }

        $validated = $request->validate(
            [
                'session_id' => [
                    'required',
                    'integer',
                ],

                'exercise_id' => [
                    'nullable',
                    'integer',
                    'required_without:exercise_name',
                ],

                'exercise_name' => [
                    'nullable',
                    'string',
                    'max:255',
                    'required_without:exercise_id',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'session_id.required' =>
                    'Session ID wajib tersedia.',

                'session_id.integer' =>
                    'Session ID tidak valid.',

                'exercise_id.required_without' =>
                    'Data exercise wajib tersedia.',

                'exercise_id.integer' =>
                    'Exercise ID tidak valid.',

                'exercise_name.required_without' =>
                    'Nama exercise wajib tersedia.',

                'exercise_name.max' =>
                    'Nama exercise terlalu panjang.',

                'notes.max' =>
                    'Catatan maksimal 1000 karakter.',
            ]
        );

        if (
            ! Schema::hasTable(
                'workout_sessions'
            )
            || ! Schema::hasTable(
                'workout_progress'
            )
            || ! Schema::hasTable(
                'exercises'
            )
        ) {
            return $this->errorResponse(
                'Struktur tabel workout belum lengkap.',
                500
            );
        }

        $sessionId =
            (int) $validated['session_id'];

        $session = DB::table(
            'workout_sessions'
        )
            ->where('id', $sessionId)
            ->where('user_id', $userId)
            ->first();

        if (! $session) {
            return $this->errorResponse(
                'Sesi workout tidak ditemukan.',
                403
            );
        }

        /*
         * Sesi yang mempunyai duration_minutes berarti
         * sudah diselesaikan.
         */
        if (
            $session->duration_minutes !== null
        ) {
            return $this->errorResponse(
                'Sesi workout ini sudah selesai.',
                422
            );
        }

        $exercise = $this->resolveExercise(
            $validated,
            (int) $session->program_id
        );

        if (! $exercise) {
            return $this->errorResponse(
                'Exercise tidak ditemukan atau tidak termasuk dalam program ini.',
                404
            );
        }

        try {
            DB::transaction(
                function () use (
                    $userId,
                    $sessionId,
                    $exercise,
                    $validated
                ): void {
                    $key = [
                        'user_id' => $userId,
                        'session_id' => $sessionId,
                        'exercise_id' =>
                            (int) $exercise->id,
                    ];

                    $existingProgress =
                        DB::table(
                            'workout_progress'
                        )
                            ->where($key)
                            ->lockForUpdate()
                            ->first();

                    $progressData = [];

                    if (
                        Schema::hasColumn(
                            'workout_progress',
                            'completed'
                        )
                    ) {
                        $progressData['completed'] =
                            1;
                    }

                    if (
                        Schema::hasColumn(
                            'workout_progress',
                            'completed_at'
                        )
                    ) {
                        $progressData['completed_at'] =
                            now();
                    }

                    if (
                        Schema::hasColumn(
                            'workout_progress',
                            'notes'
                        )
                    ) {
                        $progressData['notes'] =
                            $validated['notes']
                            ?? null;
                    }

                    if ($existingProgress) {
                        if (
                            Schema::hasColumn(
                                'workout_progress',
                                'updated_at'
                            )
                        ) {
                            $progressData['updated_at'] =
                                now();
                        }

                        if (! empty($progressData)) {
                            DB::table(
                                'workout_progress'
                            )
                                ->where($key)
                                ->update(
                                    $progressData
                                );
                        }

                        return;
                    }

                    $insertData = array_merge(
                        $key,
                        $progressData
                    );

                    if (
                        Schema::hasColumn(
                            'workout_progress',
                            'created_at'
                        )
                    ) {
                        $insertData['created_at'] =
                            now();
                    }

                    if (
                        Schema::hasColumn(
                            'workout_progress',
                            'updated_at'
                        )
                    ) {
                        $insertData['updated_at'] =
                            now();
                    }

                    DB::table(
                        'workout_progress'
                    )->insert(
                        $insertData
                    );
                }
            );

            return response()->json([
                'success' => true,

                'message' =>
                    'Progress exercise berhasil disimpan.',

                'session_id' =>
                    $sessionId,

                'program_id' =>
                    (int) $session->program_id,

                'exercise_id' =>
                    (int) $exercise->id,

                'exercise_name' =>
                    $exercise->name,
            ]);
        } catch (\Throwable $error) {
            report($error);

            return $this->errorResponse(
                'Gagal menyimpan progress exercise.',
                500
            );
        }
    }

    /**
     * Menyelesaikan workout dan menyimpan durasi.
     */
    public function finishWorkout(
        Request $request
    ): JsonResponse {
        $userId = Auth::id();

        if (! $userId) {
            return $this->errorResponse(
                'Silakan login terlebih dahulu.',
                401
            );
        }

        $validated = $request->validate(
            [
                'session_id' => [
                    'required',
                    'integer',
                ],

                'duration_minutes' => [
                    'nullable',
                    'integer',
                    'min:0',
                    'max:1440',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'session_id.required' =>
                    'Session ID wajib tersedia.',

                'session_id.integer' =>
                    'Session ID tidak valid.',

                'duration_minutes.integer' =>
                    'Durasi harus berupa angka.',

                'duration_minutes.min' =>
                    'Durasi tidak boleh negatif.',

                'duration_minutes.max' =>
                    'Durasi maksimal 1440 menit.',

                'notes.max' =>
                    'Catatan maksimal 1000 karakter.',
            ]
        );

        if (
            ! Schema::hasTable(
                'workout_sessions'
            )
        ) {
            return $this->errorResponse(
                'Tabel workout session belum tersedia.',
                500
            );
        }

        $sessionId =
            (int) $validated['session_id'];

        try {
            $result = DB::transaction(
                function () use (
                    $sessionId,
                    $userId,
                    $validated
                ): array {
                    $session = DB::table(
                        'workout_sessions'
                    )
                        ->where('id', $sessionId)
                        ->where(
                            'user_id',
                            $userId
                        )
                        ->lockForUpdate()
                        ->first();

                    if (! $session) {
                        return [
                            'status' =>
                                'not_found',
                        ];
                    }

                    /*
                     * Jangan menyelesaikan ulang sesi
                     * yang sudah mempunyai durasi.
                     */
                    if (
                        $session
                            ->duration_minutes
                        !== null
                        && (int) $session
                            ->duration_minutes > 0
                    ) {
                        return [
                            'status' =>
                                'already_completed',

                            'program_id' =>
                                (int) $session
                                    ->program_id,

                            'duration_minutes' =>
                                (int) $session
                                    ->duration_minutes,

                            'completed_exercises' =>
                                $this
                                    ->countCompletedExercises(
                                        $sessionId,
                                        $userId
                                    ),
                        ];
                    }

                    $completedExercises =
                        $this
                            ->countCompletedExercises(
                                $sessionId,
                                $userId
                            );

                    /*
                     * Minimal satu exercise harus tercatat
                     * sebelum sesi dapat diselesaikan.
                     */
                    if (
                        $completedExercises < 1
                    ) {
                        return [
                            'status' =>
                                'no_progress',
                        ];
                    }

                    $durationMinutes =
                        $this->resolveDuration(
                            $session,
                            $validated
                        );

                    $sessionData = [
                        'duration_minutes' =>
                            $durationMinutes,
                    ];

                    if (
                        array_key_exists(
                            'notes',
                            $validated
                        )
                        && Schema::hasColumn(
                            'workout_sessions',
                            'notes'
                        )
                    ) {
                        $sessionData['notes'] =
                            $validated['notes'];
                    }

                    if (
                        Schema::hasColumn(
                            'workout_sessions',
                            'updated_at'
                        )
                    ) {
                        $sessionData['updated_at'] =
                            now();
                    }

                    DB::table(
                        'workout_sessions'
                    )
                        ->where(
                            'id',
                            $sessionId
                        )
                        ->where(
                            'user_id',
                            $userId
                        )
                        ->update(
                            $sessionData
                        );

                    $this->completeUserProgram(
                        $userId,
                        (int) $session
                            ->program_id
                    );

                    return [
                        'status' => 'completed',

                        'program_id' =>
                            (int) $session
                                ->program_id,

                        'duration_minutes' =>
                            $durationMinutes,

                        'completed_exercises' =>
                            $completedExercises,
                    ];
                }
            );

            if (
                $result['status']
                === 'not_found'
            ) {
                return $this->errorResponse(
                    'Sesi workout tidak ditemukan.',
                    403
                );
            }

            if (
                $result['status']
                === 'no_progress'
            ) {
                return $this->errorResponse(
                    'Belum ada exercise yang diselesaikan pada sesi ini.',
                    422
                );
            }

            $alreadyCompleted =
                $result['status']
                === 'already_completed';

            return response()->json([
                'success' => true,

                'message' =>
                    $alreadyCompleted
                        ? 'Workout sebelumnya sudah selesai.'
                        : 'Workout berhasil diselesaikan.',

                'session_id' =>
                    $sessionId,

                'program_id' =>
                    $result['program_id'],

                'duration_minutes' =>
                    $result['duration_minutes'],

                'completed_exercises' =>
                    $result['completed_exercises'],

                'already_completed' =>
                    $alreadyCompleted,
            ]);
        } catch (\Throwable $error) {
            report($error);

            return $this->errorResponse(
                'Gagal menyelesaikan workout.',
                500
            );
        }
    }

    /**
     * Mencari exercise berdasarkan ID atau nama.
     *
     * Exercise wajib mempunyai program_id yang sama
     * dengan program pada workout session.
     */
    private function resolveExercise(
        array $validated,
        int $programId
    ): ?object {
        if (
            ! empty(
                $validated['exercise_id']
            )
        ) {
            return DB::table('exercises')
                ->where(
                    'id',
                    (int) $validated[
                        'exercise_id'
                    ]
                )
                ->where(
                    'program_id',
                    $programId
                )
                ->first();
        }

        $exerciseName =
            $this->normalizeExerciseName(
                $validated['exercise_name']
                ?? ''
            );

        if ($exerciseName === '') {
            return null;
        }

        /*
         * Collation database utf8mb4_unicode_ci
         * membuat pencarian tidak sensitif huruf besar/kecil.
         */
        return DB::table('exercises')
            ->where(
                'program_id',
                $programId
            )
            ->whereRaw(
                'TRIM(name) = ?',
                [$exerciseName]
            )
            ->first();
    }

    /**
     * Merapikan spasi nama exercise.
     */
    private function normalizeExerciseName(
        string $exerciseName
    ): string {
        $exerciseName =
            trim($exerciseName);

        $normalized = preg_replace(
            '/\s+/u',
            ' ',
            $exerciseName
        );

        return is_string($normalized)
            ? $normalized
            : $exerciseName;
    }

    /**
     * Menghitung jumlah exercise yang sudah selesai.
     */
    private function countCompletedExercises(
        int $sessionId,
        int $userId
    ): int {
        if (
            ! Schema::hasTable(
                'workout_progress'
            )
        ) {
            return 0;
        }

        $query = DB::table(
            'workout_progress'
        )
            ->where(
                'session_id',
                $sessionId
            )
            ->where(
                'user_id',
                $userId
            );

        if (
            Schema::hasColumn(
                'workout_progress',
                'completed'
            )
        ) {
            $query->where(
                'completed',
                1
            );
        }

        return $query->count();
    }

    /**
     * Menentukan durasi workout.
     *
     * Durasi dari frontend digunakan jika tersedia.
     * Jika tidak tersedia, durasi dihitung dari session_date.
     */
    private function resolveDuration(
        object $session,
        array $validated
    ): int {
        $durationMinutes =
            isset(
                $validated[
                    'duration_minutes'
                ]
            )
                ? (int) $validated[
                    'duration_minutes'
                ]
                : 0;

        if ($durationMinutes <= 0) {
            try {
                $startTime =
                    Carbon::parse(
                        $session->session_date
                    );

                $durationSeconds =
                    $startTime
                        ->diffInSeconds(
                            now()
                        );

                $durationMinutes =
                    (int) ceil(
                        $durationSeconds / 60
                    );
            } catch (\Throwable $error) {
                $durationMinutes = 1;
            }
        }

        return max(
            1,
            min(
                $durationMinutes,
                1440
            )
        );
    }

    /**
     * Menandai user program sebagai selesai.
     */
    private function completeUserProgram(
        int $userId,
        int $programId
    ): void {
        if (
            ! Schema::hasTable(
                'user_programs'
            )
        ) {
            return;
        }

        $query = DB::table(
            'user_programs'
        )
            ->where(
                'user_id',
                $userId
            )
            ->where(
                'program_id',
                $programId
            );

        if (
            Schema::hasColumn(
                'user_programs',
                'status'
            )
        ) {
            $query->where(
                'status',
                'ongoing'
            );
        }

        $userProgram =
            $query
                ->orderByDesc('id')
                ->first();

        if (! $userProgram) {
            return;
        }

        $updateData = [];

        if (
            Schema::hasColumn(
                'user_programs',
                'status'
            )
        ) {
            $updateData['status'] =
                'completed';
        }

        if (
            Schema::hasColumn(
                'user_programs',
                'completed_at'
            )
        ) {
            $updateData['completed_at'] =
                now();
        }

        if (
            Schema::hasColumn(
                'user_programs',
                'updated_at'
            )
        ) {
            $updateData['updated_at'] =
                now();
        }

        if (! empty($updateData)) {
            DB::table('user_programs')
                ->where(
                    'id',
                    $userProgram->id
                )
                ->update(
                    $updateData
                );
        }
    }

    /**
     * Format respons error JSON.
     */
    private function errorResponse(
        string $message,
        int $status
    ): JsonResponse {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
            ],
            $status
        );
    }
}