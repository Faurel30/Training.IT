<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_if(! $user, 401);

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->name ?? $user->username,
                'gender' => $user->gender,
                'profile_completed' => 0,
            ]
        );

        $bmiData = $this->calculateBmi(
            $profile->weight,
            $profile->height
        );

        $stats = $this->getWorkoutStats($user->id);

        $programProgress = $this->getProgramProgress($user->id);

        $recentWorkouts = $this->getRecentWorkouts($user->id);

        $profileCompletion = $this->calculateProfileCompletion(
            $user,
            $profile
        );

        return view('profile.index', [
            'user' => $user,
            'profile' => $profile,
            'stats' => $stats,
            'programProgress' => $programProgress,
            'recentWorkouts' => $recentWorkouts,
            'profileCompletion' => $profileCompletion,
            'bmi' => $bmiData['value'],
            'bmiCategory' => $bmiData['category'],
        ]);
    }

    /**
     * Menampilkan halaman edit profil.
     */
    public function edit(): View
    {
        $user = Auth::user();

        abort_if(! $user, 401);

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->name ?? $user->username,
                'gender' => $user->gender,
                'profile_completed' => 0,
            ]
        );

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Memperbarui profil pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_if(! $user, 401);

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                ],

                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],

                'weight' => [
                    'required',
                    'numeric',
                    'between:20,400',
                ],

                'height' => [
                    'required',
                    'numeric',
                    'between:80,250',
                ],

                'age' => [
                    'required',
                    'integer',
                    'between:10,100',
                ],

                'fitness_level' => [
                    'nullable',
                    Rule::in([
                        'beginner',
                        'intermediate',
                        'advanced',
                    ]),
                ],

                'userBio' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.min' => 'Nama lengkap minimal 2 karakter.',
                'name.max' => 'Nama lengkap maksimal 100 karakter.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email tersebut sudah digunakan.',

                'weight.required' => 'Berat badan wajib diisi.',
                'weight.numeric' => 'Berat badan harus berupa angka.',
                'weight.between' => 'Berat badan harus antara 20–400 kg.',

                'height.required' => 'Tinggi badan wajib diisi.',
                'height.numeric' => 'Tinggi badan harus berupa angka.',
                'height.between' => 'Tinggi badan harus antara 80–250 cm.',

                'age.required' => 'Umur wajib diisi.',
                'age.integer' => 'Umur harus berupa angka bulat.',
                'age.between' => 'Umur harus antara 10–100 tahun.',

                'fitness_level.in' => 'Level kebugaran tidak valid.',

                'userBio.max' => 'Bio maksimal 1000 karakter.',
            ]
        );

        $profile = Profile::firstOrNew([
            'user_id' => $user->id,
        ]);

        DB::transaction(function () use (
            $user,
            $profile,
            $validated
        ): void {
            $fullName = trim($validated['name']);
            $email = Str::lower(trim($validated['email']));

            /*
             * Update data pada tabel users.
             */
            if (Schema::hasColumn('users', 'name')) {
                $user->name = $fullName;
            }

            if (Schema::hasColumn('users', 'email')) {
                $user->email = $email;
            }

            $user->save();

            /*
             * Update data pada tabel profiles.
             */
            $profile->user_id = $user->id;
            $profile->full_name = $fullName;
            $profile->age = (int) $validated['age'];
            $profile->height = (float) $validated['height'];
            $profile->weight = (float) $validated['weight'];
            $profile->gender = $user->gender;

            if (
                Schema::hasColumn('profiles', 'fitness_level')
            ) {
                $profile->fitness_level =
                    $validated['fitness_level']
                    ?? $profile->fitness_level
                    ?? 'beginner';
            }

            $profile->goal = isset($validated['userBio'])
                ? trim($validated['userBio'])
                : null;

            $profile->profile_completed = $profile
                ->isCompleteFor($user);

            $profile->save();
        });

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }

    /**
     * Mengambil statistik workout pengguna.
     */
    private function getWorkoutStats(int $userId): array
    {
        $defaultStats = [
            'total_workouts' => 0,
            'total_minutes' => 0,
            'this_month' => 0,
            'average_duration' => 0,
        ];

        if (! Schema::hasTable('workout_sessions')) {
            return $defaultStats;
        }

        $completedQuery = $this->completedWorkoutQuery($userId);

        $totalWorkouts = (clone $completedQuery)->count();

        $totalMinutes = 0;

        if (
            Schema::hasColumn(
                'workout_sessions',
                'duration_minutes'
            )
        ) {
            $totalMinutes = (int) (
                (clone $completedQuery)
                    ->sum('duration_minutes')
            );
        }

        $thisMonth = 0;

        $dateColumn = $this->getWorkoutDateColumn();

        if ($dateColumn !== null) {
            $thisMonth = (clone $completedQuery)
                ->whereYear($dateColumn, now()->year)
                ->whereMonth($dateColumn, now()->month)
                ->count();
        }

        $averageDuration = $totalWorkouts > 0
            ? (int) round($totalMinutes / $totalWorkouts)
            : 0;

        return [
            'total_workouts' => $totalWorkouts,
            'total_minutes' => $totalMinutes,
            'this_month' => $thisMonth,
            'average_duration' => $averageDuration,
        ];
    }

    /**
     * Menghitung jumlah workout berdasarkan program.
     *
     * Mapping program:
     * 1 = Gym
     * 2 = Cardio
     * 3 = Calisthenics
     */
    private function getProgramProgress(int $userId): array
    {
        $progress = [
            'gym' => 0,
            'cardio' => 0,
            'calisthenics' => 0,
        ];

        if (
            ! Schema::hasTable('workout_sessions')
            || ! Schema::hasColumn(
                'workout_sessions',
                'program_id'
            )
        ) {
            return $progress;
        }

        $rows = $this->completedWorkoutQuery($userId)
            ->select(
                'program_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('program_id')
            ->pluck('total', 'program_id');

        $progress['gym'] = (int) ($rows[1] ?? 0);
        $progress['cardio'] = (int) ($rows[2] ?? 0);
        $progress['calisthenics'] = (int) ($rows[3] ?? 0);

        return $progress;
    }

    /**
     * Mengambil lima workout terakhir.
     */
    private function getRecentWorkouts(int $userId): array
    {
        if (! Schema::hasTable('workout_sessions')) {
            return [];
        }

        $columns = ['id'];

        if (
            Schema::hasColumn(
                'workout_sessions',
                'program_id'
            )
        ) {
            $columns[] = 'program_id';
        }

        if (
            Schema::hasColumn(
                'workout_sessions',
                'duration_minutes'
            )
        ) {
            $columns[] = 'duration_minutes';
        }

        $dateColumn = $this->getWorkoutDateColumn();

        if ($dateColumn !== null) {
            $columns[] = $dateColumn;
        }

        $query = $this->completedWorkoutQuery($userId)
            ->select($columns);

        if ($dateColumn !== null) {
            $query->orderByDesc($dateColumn);
        } else {
            $query->orderByDesc('id');
        }

        return $query
            ->limit(5)
            ->get()
            ->map(function ($workout) use ($dateColumn): array {
                $programId = isset($workout->program_id)
                    ? (int) $workout->program_id
                    : null;

                return [
                    'id' => $workout->id,
                    'program_id' => $programId,
                    'program_name' => $this->programName(
                        $programId
                    ),
                    'duration_minutes' =>
                        (int) ($workout->duration_minutes ?? 0),
                    'date' => $dateColumn !== null
                        ? $workout->{$dateColumn}
                        : null,
                ];
            })
            ->all();
    }

    /**
     * Query dasar untuk sesi workout yang dianggap selesai.
     */
    private function completedWorkoutQuery(
        int $userId
    ): Builder {
        $query = DB::table('workout_sessions')
            ->where('user_id', $userId);

        $hasStatus = Schema::hasColumn(
            'workout_sessions',
            'status'
        );

        $hasDuration = Schema::hasColumn(
            'workout_sessions',
            'duration_minutes'
        );

        if ($hasStatus && $hasDuration) {
            $query->where(function (Builder $builder): void {
                $builder
                    ->where('status', 'completed')
                    ->orWhere('duration_minutes', '>', 0);
            });
        } elseif ($hasStatus) {
            $query->where('status', 'completed');
        } elseif ($hasDuration) {
            $query->where('duration_minutes', '>', 0);
        }

        return $query;
    }

    /**
     * Menentukan kolom tanggal workout yang tersedia.
     */
    private function getWorkoutDateColumn(): ?string
    {
        if (
            Schema::hasColumn(
                'workout_sessions',
                'session_date'
            )
        ) {
            return 'session_date';
        }

        if (
            Schema::hasColumn(
                'workout_sessions',
                'created_at'
            )
        ) {
            return 'created_at';
        }

        return null;
    }

    /**
     * Menghitung persentase kelengkapan profil.
     */
    private function calculateProfileCompletion(
        $user,
        Profile $profile
    ): int {
        $fields = [
            $profile->full_name,
            $user->email,
            $profile->gender,
            $profile->age,
            $profile->height,
            $profile->weight,
            $profile->fitness_level,
            $profile->goal,
        ];

        $completedFields = collect($fields)
            ->filter(function ($value): bool {
                return $value !== null
                    && $value !== '';
            })
            ->count();

        return (int) round(
            ($completedFields / count($fields)) * 100
        );
    }

    /**
     * Menghitung BMI.
     */
    private function calculateBmi(
        $weight,
        $height
    ): array {
        $weight = (float) $weight;
        $height = (float) $height;

        if ($weight <= 0 || $height <= 0) {
            return [
                'value' => null,
                'category' => 'Belum tersedia',
            ];
        }

        $heightInMeters = $height / 100;

        $bmi = round(
            $weight / ($heightInMeters ** 2),
            1
        );

        $category = match (true) {
            $bmi < 18.5 => 'Berat badan kurang',
            $bmi < 25 => 'Normal',
            $bmi < 30 => 'Berat badan berlebih',
            default => 'Obesitas',
        };

        return [
            'value' => $bmi,
            'category' => $category,
        ];
    }

    /**
     * Mengubah ID program menjadi nama program.
     */
    private function programName(?int $programId): string
    {
        return match ($programId) {
            1 => 'Gym Training',
            2 => 'Cardio Training',
            3 => 'Calisthenics Training',
            default => 'Workout',
        };
    }
}