<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Cardio Training Program</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/cardio.css') }}?v=20260715-6"
    >

    <style>
        .workout-status {
            display: none;
            margin-top: 14px;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.6;
        }

        .workout-status.visible {
            display: block;
        }

        .workout-status.success {
            color: #a9efb1;
            border: 1px solid rgba(77, 190, 94, 0.35);
            background: rgba(77, 190, 94, 0.12);
        }

        .workout-status.error {
            color: #ffaaaa;
            border: 1px solid rgba(255, 75, 75, 0.35);
            background: rgba(255, 75, 75, 0.1);
        }

        .exercise-check:disabled {
            cursor: wait;
        }

        .finish-btn:disabled {
            cursor: wait;
            opacity: 0.65;
        }
    </style>
</head>

<body>

@php
    $profileCompleted = $profileCompleted ?? false;
@endphp

<div class="container">

    {{-- Header --}}
    <header class="header">
        <h1>CARDIO PROGRAM</h1>

        <p>
            Program kardio untuk membakar kalori, meningkatkan stamina,
            dan menjaga kesehatan jantung.
        </p>
    </header>

    {{-- Workout Tracker --}}
    <section
        class="tracker-card"
        aria-label="Cardio workout progress"
    >
        <div class="tracker-top">
            <div>
                <span class="tracker-label">
                    Workout Progress
                </span>

                <h2 id="progressText">
                    0 of 4 exercises completed
                </h2>
            </div>

            <button
                class="finish-btn"
                id="finishWorkoutBtn"
                type="button"
            >
                Finish Workout
            </button>
        </div>

        <div
            class="progress-bar"
            aria-hidden="true"
        >
            <div
                class="progress-fill"
                id="progressFill"
            ></div>
        </div>

        <p class="tracker-note">
            Centang setiap latihan setelah selesai. Progress akan
            disimpan secara otomatis ke akun Anda.
        </p>

        <div
            class="workout-status"
            id="workoutStatus"
            role="status"
        ></div>
    </section>

    {{-- Cardio Dasar --}}
    <section class="workout-section">
        <h2>Cardio Dasar</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                    data-exercise-id="13"
                >

                <span class="exercise-name">
                    Jogging
                </span>

                <span class="exercise-sets">
                    15–20 Menit
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                    data-exercise-id="14"
                >

                <span class="exercise-name">
                    Jump Rope
                </span>

                <span class="exercise-sets">
                    3x1 Menit
                </span>
            </label>

        </div>
    </section>

    {{-- Cardio Intensif --}}
    <section class="workout-section">
        <h2>Cardio Intensif</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                    data-exercise-id="15"
                >

                <span class="exercise-name">
                    Running
                </span>

                <span class="exercise-sets">
                    25–30 Menit
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                    data-exercise-id="16"
                >

                <span class="exercise-name">
                    HIIT (Sprint 30 detik + Jalan 1 menit)
                </span>

                <span class="exercise-sets">
                    Repeat 6–8x
                </span>
            </label>

        </div>
    </section>

    {{-- Catatan --}}
    <section class="note-section">
        <h3>Catatan Penting:</h3>

        <p>
            Lakukan pemanasan sebelum latihan dan pendinginan setelah
            menyelesaikan seluruh rangkaian cardio.
        </p>

        <ul>
            <li>
                Sesuaikan intensitas dengan kondisi dan kemampuan tubuh.
            </li>

            <li>
                Beristirahat jika mengalami pusing, nyeri, atau sesak.
            </li>

            <li>
                Pastikan tubuh mendapatkan cukup cairan.
            </li>

            <li>
                Selesaikan empat latihan untuk mencatat satu sesi Cardio.
            </li>
        </ul>
    </section>

</div>

{{-- Profile Completion Modal --}}
@if (! $profileCompleted)
    <div
        class="modal-overlay"
        id="completionModal"
        aria-hidden="true"
    >
        <div
            class="completion-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="completionTitle"
        >
            <p class="completion-tag">
                Workout Complete
            </p>

            <h2 id="completionTitle">
                Mau lengkapi profil sekarang?
            </h2>

            <p class="completion-copy">
                Lengkapi profil agar statistik latihan dan informasi
                kebugaran Anda tampil lebih lengkap.
            </p>

            <div class="modal-actions">
                <button
                    class="modal-btn primary"
                    id="profileNowBtn"
                    type="button"
                >
                    Lengkapi Sekarang
                </button>

                <button
                    class="modal-btn secondary"
                    id="profileLaterBtn"
                    type="button"
                >
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfMeta = document.querySelector(
        'meta[name="csrf-token"]'
    );

    const csrfToken = csrfMeta
        ? csrfMeta.getAttribute('content')
        : '';

    const checkboxes = Array.from(
        document.querySelectorAll('.exercise-check')
    );

    const progressText =
        document.getElementById('progressText');

    const progressFill =
        document.getElementById('progressFill');

    const finishWorkoutBtn =
        document.getElementById('finishWorkoutBtn');

    const workoutStatus =
        document.getElementById('workoutStatus');

    const completionModal =
        document.getElementById('completionModal');

    const profileNowBtn =
        document.getElementById('profileNowBtn');

    const profileLaterBtn =
        document.getElementById('profileLaterBtn');

    const shouldPromptProfile =
        @json(! $profileCompleted);

    const progressStorageKey =
        'workoutProgress:cardio-training';

    const sessionStorageKey =
        'workoutSessionId:cardio';

    const startTimeStorageKey =
        'workoutStartedAt:cardio';

    const completedStorageKey =
        'workoutCompleted:cardio';

    let currentSessionId =
        localStorage.getItem(sessionStorageKey);

    let startSessionPromise = null;

    /*
     * Menampilkan pesan di tracker tanpa selalu menggunakan alert.
     */
    function showStatus(message, type) {
        if (!workoutStatus) {
            return;
        }

        workoutStatus.textContent = message;

        workoutStatus.className =
            `workout-status visible ${type}`;
    }

    function clearStatus() {
        if (!workoutStatus) {
            return;
        }

        workoutStatus.textContent = '';
        workoutStatus.className = 'workout-status';
    }

    /*
     * Helper request JSON.
     */
    async function requestJson(url, options) {
        const response = await fetch(url, options);

        let json = {};

        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        if (!response.ok || !json.success) {
            const requestError = new Error(
                json.message ||
                'Terjadi kesalahan saat menghubungi server.'
            );

            requestError.status = response.status;

            throw requestError;
        }

        return json;
    }

    /*
     * Membaca progress dari localStorage.
     * LocalStorage hanya untuk mempertahankan checkbox ketika refresh.
     */
    function getSavedProgress() {
        try {
            const savedValue =
                localStorage.getItem(progressStorageKey);

            return savedValue
                ? JSON.parse(savedValue)
                : [];
        } catch (error) {
            console.error(
                'Gagal membaca progress Cardio:',
                error
            );

            return [];
        }
    }

    function persistProgress() {
        localStorage.setItem(
            progressStorageKey,
            JSON.stringify(
                checkboxes.map(function (checkbox) {
                    return checkbox.checked;
                })
            )
        );
    }

    function getCompletedCount() {
        return checkboxes.filter(
            function (checkbox) {
                return checkbox.checked;
            }
        ).length;
    }

    function updateProgress() {
        const completedCount =
            getCompletedCount();

        const totalCount =
            checkboxes.length;

        const percentage =
            totalCount === 0
                ? 0
                : Math.round(
                    (completedCount / totalCount) * 100
                );

        if (progressText) {
            progressText.textContent =
                `${completedCount} of ${totalCount} exercises completed`;
        }

        if (progressFill) {
            progressFill.style.width =
                `${percentage}%`;
        }

        if (
            finishWorkoutBtn
            && !finishWorkoutBtn.disabled
        ) {
            finishWorkoutBtn.textContent =
                completedCount === totalCount
                && totalCount > 0
                    ? 'Finish Workout'
                    : 'Finish Workout';
        }
    }

    /*
     * Memulai session Cardio.
     * Program ID Cardio = 2.
     */
    async function startSession(forceNew = false) {
        if (forceNew) {
            currentSessionId = null;

            localStorage.removeItem(
                sessionStorageKey
            );

            localStorage.removeItem(
                startTimeStorageKey
            );
        }

        if (currentSessionId) {
            return currentSessionId;
        }

        if (startSessionPromise) {
            return startSessionPromise;
        }

        startSessionPromise = requestJson(
            '{{ route('workout.startSession') }}',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: JSON.stringify({
                    program_id: 2
                })
            }
        )
        .then(function (json) {
            currentSessionId =
                String(json.session_id);

            localStorage.setItem(
                sessionStorageKey,
                currentSessionId
            );

            if (
                !localStorage.getItem(
                    startTimeStorageKey
                )
            ) {
                localStorage.setItem(
                    startTimeStorageKey,
                    String(Date.now())
                );
            }

            return currentSessionId;
        })
        .finally(function () {
            startSessionPromise = null;
        });

        return startSessionPromise;
    }

    /*
     * Menyimpan exercise menggunakan exercise_id database.
     */
    async function saveExerciseProgress(
        sessionId,
        exerciseId
    ) {
        return requestJson(
            '{{ route('workout.saveProgress') }}',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: JSON.stringify({
                    session_id: sessionId,
                    exercise_id: Number(exerciseId)
                })
            }
        );
    }

    /*
     * Menyelesaikan workout.
     */
    async function finishWorkoutSession(
        sessionId,
        durationMinutes
    ) {
        return requestJson(
            '{{ route('workout.finishWorkout') }}',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: JSON.stringify({
                    session_id: sessionId,
                    duration_minutes: durationMinutes
                })
            }
        );
    }

    /*
     * Menghitung durasi sejak latihan pertama dicentang.
     */
    function calculateDurationMinutes() {
        const startedAt = Number(
            localStorage.getItem(
                startTimeStorageKey
            )
        );

        if (!startedAt) {
            return 1;
        }

        const elapsedMilliseconds =
            Date.now() - startedAt;

        return Math.max(
            1,
            Math.ceil(
                elapsedMilliseconds / 60000
            )
        );
    }

    function resetWorkoutState() {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
            checkbox.disabled = false;

            const exerciseItem =
                checkbox.closest('.exercise-item');

            if (exerciseItem) {
                exerciseItem.classList.remove(
                    'completed'
                );
            }
        });

        localStorage.removeItem(
            progressStorageKey
        );

        localStorage.removeItem(
            sessionStorageKey
        );

        localStorage.removeItem(
            startTimeStorageKey
        );

        currentSessionId = null;

        updateProgress();
    }

    /*
     * Memuat progress lama.
     */
    const savedProgress = getSavedProgress();

    checkboxes.forEach(function (checkbox, index) {
        checkbox.checked =
            Boolean(savedProgress[index]);

        const exerciseItem =
            checkbox.closest('.exercise-item');

        if (exerciseItem) {
            exerciseItem.classList.toggle(
                'completed',
                checkbox.checked
            );
        }
    });

    /*
     * Event checkbox.
     */
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener(
            'change',
            async function () {
                clearStatus();

                const exerciseItem =
                    checkbox.closest('.exercise-item');

                if (exerciseItem) {
                    exerciseItem.classList.toggle(
                        'completed',
                        checkbox.checked
                    );
                }

                persistProgress();
                updateProgress();

                if (!checkbox.checked) {
                    return;
                }

                const exerciseId =
                    checkbox.dataset.exerciseId;

                if (!exerciseId) {
                    checkbox.checked = false;

                    if (exerciseItem) {
                        exerciseItem.classList.remove(
                            'completed'
                        );
                    }

                    persistProgress();
                    updateProgress();

                    showStatus(
                        'Exercise ID tidak ditemukan pada halaman.',
                        'error'
                    );

                    return;
                }

                checkbox.disabled = true;

                try {
                    let sessionId =
                        await startSession();

                    try {
                        await saveExerciseProgress(
                            sessionId,
                            exerciseId
                        );
                    } catch (error) {
                        /*
                         * Jika localStorage menyimpan session lama,
                         * buat session baru dan coba sekali lagi.
                         */
                        if (
                            error.status === 403
                            || error.status === 422
                        ) {
                            sessionId =
                                await startSession(true);

                            await saveExerciseProgress(
                                sessionId,
                                exerciseId
                            );
                        } else {
                            throw error;
                        }
                    }

                    showStatus(
                        'Progress latihan berhasil disimpan.',
                        'success'
                    );
                } catch (error) {
                    console.error(
                        'Save Cardio progress error:',
                        error
                    );

                    checkbox.checked = false;

                    if (exerciseItem) {
                        exerciseItem.classList.remove(
                            'completed'
                        );
                    }

                    persistProgress();
                    updateProgress();

                    showStatus(
                        'Progress belum berhasil disimpan: '
                        + error.message,
                        'error'
                    );
                } finally {
                    checkbox.disabled = false;
                }
            }
        );
    });

    /*
     * Tombol Finish Workout.
     */
    finishWorkoutBtn.addEventListener(
        'click',
        async function () {
            clearStatus();

            const completedCount =
                getCompletedCount();

            const totalCount =
                checkboxes.length;

            if (completedCount === 0) {
                alert(
                    'Centang minimal satu latihan terlebih dahulu.'
                );

                return;
            }

            if (completedCount < totalCount) {
                alert(
                    'Selesaikan semua latihan Cardio terlebih dahulu.'
                );

                return;
            }

            finishWorkoutBtn.disabled = true;
            finishWorkoutBtn.textContent =
                'Menyimpan...';

            try {
                const sessionId =
                    await startSession();

                const durationMinutes =
                    calculateDurationMinutes();

                await finishWorkoutSession(
                    sessionId,
                    durationMinutes
                );

                localStorage.setItem(
                    completedStorageKey,
                    new Date().toISOString()
                );

                resetWorkoutState();

                showStatus(
                    'Workout Cardio berhasil diselesaikan.',
                    'success'
                );

                if (
                    shouldPromptProfile
                    && completionModal
                ) {
                    showModal();
                } else {
                    window.location.href =
                        '{{ route('profile') }}';
                }
            } catch (error) {
                console.error(
                    'Finish Cardio error:',
                    error
                );

                showStatus(
                    'Workout belum berhasil disimpan: '
                    + error.message,
                    'error'
                );

                finishWorkoutBtn.disabled = false;
                updateProgress();
            }
        }
    );

    /*
     * Modal profile.
     */
    if (
        completionModal
        && profileNowBtn
        && profileLaterBtn
    ) {
        profileNowBtn.addEventListener(
            'click',
            function () {
                window.location.href =
                    '{{ route('profile') }}';
            }
        );

        profileLaterBtn.addEventListener(
            'click',
            function () {
                hideModal();

                window.location.href =
                    '{{ route('programs') }}';
            }
        );

        completionModal.addEventListener(
            'click',
            function (event) {
                if (event.target === completionModal) {
                    hideModal();
                }
            }
        );
    }

    function showModal() {
        if (!completionModal) {
            return;
        }

        completionModal.classList.add('visible');

        completionModal.setAttribute(
            'aria-hidden',
            'false'
        );
    }

    function hideModal() {
        if (!completionModal) {
            return;
        }

        completionModal.classList.remove('visible');

        completionModal.setAttribute(
            'aria-hidden',
            'true'
        );
    }

    updateProgress();
});
</script>

</body>
</html>