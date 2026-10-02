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

    <title>Calisthenics Training Program</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/chalisthenic.css') }}?v=20260715-5"
    >

    <style>
        .level-selector {
            width: min(900px, 100%);
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
            margin: 0 auto 28px;
        }

        .level-button {
            position: relative;
            min-height: 88px;
            padding: 17px 20px;
            border: 1px solid rgba(255, 90, 90, 0.25);
            border-radius: 18px;
            color: #ffffff;
            text-align: left;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.055);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                background 0.25s ease,
                box-shadow 0.25s ease;
        }

        .level-button:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 90, 90, 0.55);
            background: rgba(255, 90, 90, 0.1);
        }

        .level-button.active {
            border-color: rgba(255, 90, 90, 0.82);
            background:
                linear-gradient(
                    135deg,
                    rgba(255, 65, 65, 0.24),
                    rgba(95, 0, 0, 0.24)
                );
            box-shadow: 0 12px 30px rgba(255, 50, 50, 0.16);
        }

        .level-button.completed::after {
            content: "✓";
            position: absolute;
            top: 14px;
            right: 15px;
            width: 25px;
            height: 25px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            background: #39a852;
        }

        .level-button strong {
            display: block;
            margin-bottom: 6px;
            color: #ffffff;
            font-size: 15px;
        }

        .level-button span {
            color: rgba(255, 255, 255, 0.68);
            font-size: 11px;
            line-height: 1.5;
        }

        .workout-level[hidden] {
            display: none !important;
        }

        .active-level-badge {
            display: inline-flex;
            align-items: center;
            margin-bottom: 7px;
            padding: 6px 11px;
            border: 1px solid rgba(255, 90, 90, 0.25);
            border-radius: 999px;
            color: #ff9696;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            background: rgba(255, 70, 70, 0.1);
        }

        .finish-btn:disabled {
            opacity: 0.65;
            cursor: wait;
        }

        @media (max-width: 700px) {
            .level-selector {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .level-button {
                min-height: 76px;
            }
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
        <h1>CALISTHENICS PROGRAM</h1>

        <p>
            Program latihan calisthenics untuk membangun kekuatan,
            kontrol tubuh, stabilitas, dan endurance.
        </p>
    </header>

    {{-- Level Selector --}}
    <section
        class="level-selector"
        aria-label="Pilih level latihan"
    >
        <button
            type="button"
            class="level-button active"
            data-level="beginner"
        >
            <strong>Beginner</strong>

            <span>
                Day 1 · Dasar calisthenics · 4 latihan
            </span>
        </button>

        <button
            type="button"
            class="level-button"
            data-level="intermediate"
        >
            <strong>Intermediate</strong>

            <span>
                Day 3 · Latihan lanjutan · 4 latihan
            </span>
        </button>
    </section>

    {{-- Workout Tracker --}}
    <section
        class="tracker-card"
        aria-label="Calisthenics workout progress"
    >
        <div class="tracker-top">
            <div>
                <span
                    class="active-level-badge"
                    id="activeLevelBadge"
                >
                    Day 1 · Beginner
                </span>

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
                Finish Beginner
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
            Pilih satu level dan centang semua latihan pada level tersebut.
            Progress disimpan secara otomatis.
        </p>
    </section>

    {{-- Beginner --}}
    <section
        class="workout-section workout-level"
        data-level="beginner"
    >
        <h2>Beginner Calisthenics</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Push Up
                </span>

                <span class="exercise-sets">
                    3x10
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Pull Up (assist)
                </span>

                <span class="exercise-sets">
                    3x5
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Squat
                </span>

                <span class="exercise-sets">
                    3x15
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Plank
                </span>

                <span class="exercise-sets">
                    3x30 detik
                </span>
            </label>

        </div>
    </section>

    {{-- Intermediate --}}
    <section
        class="workout-section workout-level"
        data-level="intermediate"
        hidden
    >
        <h2>Intermediate Calisthenics</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Pull Up
                </span>

                <span class="exercise-sets">
                    3x8
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Dips
                </span>

                <span class="exercise-sets">
                    3x10
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Hanging Leg Raise
                </span>

                <span class="exercise-sets">
                    3x10
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >

                <span class="exercise-name">
                    Pike Push Up
                </span>

                <span class="exercise-sets">
                    3x8
                </span>
            </label>

        </div>
    </section>

    {{-- Notes --}}
    <section class="note-section">
        <h3>Catatan Penting:</h3>

        <p>
            Program tidak perlu diselesaikan seluruhnya dalam satu hari.
            Pilih latihan sesuai level dan jadwal Anda.
        </p>

        <ul>
            <li>
                <strong>Day 1:</strong>
                Beginner Calisthenics
            </li>

            <li>
                <strong>Day 2:</strong>
                Rest atau mobility
            </li>

            <li>
                <strong>Day 3:</strong>
                Intermediate Calisthenics
            </li>

            <li>
                <strong>Day 4:</strong>
                Rest atau mengulang Beginner
            </li>
        </ul>

        <p>
            Gunakan teknik yang benar dan jangan memaksakan gerakan
            yang belum sesuai dengan kemampuan tubuh.
        </p>
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

    const levelButtons = Array.from(
        document.querySelectorAll('.level-button')
    );

    const levelSections = Array.from(
        document.querySelectorAll('.workout-level')
    );

    const progressText =
        document.getElementById('progressText');

    const progressFill =
        document.getElementById('progressFill');

    const activeLevelBadge =
        document.getElementById('activeLevelBadge');

    const finishWorkoutBtn =
        document.getElementById('finishWorkoutBtn');

    const completionModal =
        document.getElementById('completionModal');

    const profileNowBtn =
        document.getElementById('profileNowBtn');

    const profileLaterBtn =
        document.getElementById('profileLaterBtn');

    const shouldPromptProfile =
        @json(! $profileCompleted);

    const activeLevelStorageKey =
        'workoutActiveLevel:calisthenics';

    const lockedLevelStorageKey =
        'workoutLockedLevel:calisthenics';

    const levelInformation = {
        beginner: {
            title: 'Beginner',
            badge: 'Day 1 · Beginner'
        },

        intermediate: {
            title: 'Intermediate',
            badge: 'Day 3 · Intermediate'
        }
    };

    let activeLevel =
        localStorage.getItem(activeLevelStorageKey)
        || 'beginner';

    let startSessionPromise = null;

    if (!levelInformation[activeLevel]) {
        activeLevel = 'beginner';
    }

    function progressStorageKey(level) {
        return `workoutProgress:calisthenics:${level}`;
    }

    function sessionStorageKey(level) {
        return `workoutSessionId:calisthenics:${level}`;
    }

    function startTimeStorageKey(level) {
        return `workoutStartedAt:calisthenics:${level}`;
    }

    function completedStorageKey(level) {
        return `workoutCompleted:calisthenics:${level}`;
    }

    function getLevelSection(level) {
        return document.querySelector(
            `.workout-level[data-level="${level}"]`
        );
    }

    function getLevelCheckboxes(level) {
        const section = getLevelSection(level);

        if (!section) {
            return [];
        }

        return Array.from(
            section.querySelectorAll('.exercise-check')
        );
    }

    function getCompletedCount(level) {
        return getLevelCheckboxes(level).filter(
            function (checkbox) {
                return checkbox.checked;
            }
        ).length;
    }

    function getSavedProgress(level) {
        try {
            return JSON.parse(
                localStorage.getItem(
                    progressStorageKey(level)
                ) || '[]'
            );
        } catch (error) {
            console.error(
                'Gagal membaca progress Calisthenics.',
                error
            );

            return [];
        }
    }

    function loadLevelProgress(level) {
        const checkboxes =
            getLevelCheckboxes(level);

        const savedProgress =
            getSavedProgress(level);

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
    }

    function persistLevelProgress(level) {
        const checkboxes =
            getLevelCheckboxes(level);

        localStorage.setItem(
            progressStorageKey(level),
            JSON.stringify(
                checkboxes.map(function (checkbox) {
                    return checkbox.checked;
                })
            )
        );
    }

    function getLockedLevel() {
        return localStorage.getItem(
            lockedLevelStorageKey
        );
    }

    function lockLevel(level) {
        localStorage.setItem(
            lockedLevelStorageKey,
            level
        );
    }

    function unlockLevel() {
        localStorage.removeItem(
            lockedLevelStorageKey
        );
    }

    function updateCompletedButtons() {
        levelButtons.forEach(function (button) {
            const level = button.dataset.level;

            const completed = Boolean(
                localStorage.getItem(
                    completedStorageKey(level)
                )
            );

            button.classList.toggle(
                'completed',
                completed
            );
        });
    }

    function showLevel(level) {
        const selectedLevel = String(level);

        if (!levelInformation[selectedLevel]) {
            return;
        }

        const lockedLevel = getLockedLevel();

        if (
            lockedLevel
            && lockedLevel !== selectedLevel
        ) {
            alert(
                `Kamu sedang menjalankan latihan `
                + `${levelInformation[lockedLevel].title}. `
                + `Selesaikan sesi tersebut sebelum berpindah level.`
            );

            return;
        }

        activeLevel = selectedLevel;

        localStorage.setItem(
            activeLevelStorageKey,
            activeLevel
        );

        levelButtons.forEach(function (button) {
            button.classList.toggle(
                'active',
                button.dataset.level === activeLevel
            );
        });

        levelSections.forEach(function (section) {
            section.hidden =
                section.dataset.level !== activeLevel;
        });

        updateProgress();
    }

    function updateProgress() {
        const checkboxes =
            getLevelCheckboxes(activeLevel);

        const completedCount =
            getCompletedCount(activeLevel);

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

        if (activeLevelBadge) {
            activeLevelBadge.textContent =
                levelInformation[activeLevel].badge;
        }

        if (finishWorkoutBtn) {
            finishWorkoutBtn.textContent =
                `Finish ${levelInformation[activeLevel].title}`;
        }
    }

    async function requestJson(url, options) {
        const response = await fetch(url, options);

        let json = {};

        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        if (!response.ok || !json.success) {
            throw new Error(
                json.message ||
                'Terjadi kesalahan saat menghubungi server.'
            );
        }

        return json;
    }

    async function startSession(level) {
        const existingSessionId =
            localStorage.getItem(
                sessionStorageKey(level)
            );

        if (existingSessionId) {
            return existingSessionId;
        }

        if (startSessionPromise) {
            return startSessionPromise;
        }

        lockLevel(level);

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
                    program_id: 3
                })
            }
        )
        .then(function (json) {
            const sessionId =
                String(json.session_id);

            localStorage.setItem(
                sessionStorageKey(level),
                sessionId
            );

            if (
                !localStorage.getItem(
                    startTimeStorageKey(level)
                )
            ) {
                localStorage.setItem(
                    startTimeStorageKey(level),
                    String(Date.now())
                );
            }

            return sessionId;
        })
        .catch(function (error) {
            unlockLevel();
            throw error;
        })
        .finally(function () {
            startSessionPromise = null;
        });

        return startSessionPromise;
    }

    async function saveExerciseProgress(
        sessionId,
        exerciseName
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
                    exercise_name: exerciseName
                })
            }
        );
    }

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

    function calculateDuration(level) {
        const startedAt = Number(
            localStorage.getItem(
                startTimeStorageKey(level)
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

    function resetLevelProgress(level) {
        const checkboxes =
            getLevelCheckboxes(level);

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;

            const exerciseItem =
                checkbox.closest('.exercise-item');

            if (exerciseItem) {
                exerciseItem.classList.remove(
                    'completed'
                );
            }
        });

        localStorage.removeItem(
            progressStorageKey(level)
        );

        localStorage.removeItem(
            sessionStorageKey(level)
        );

        localStorage.removeItem(
            startTimeStorageKey(level)
        );

        unlockLevel();

        updateProgress();
    }

    levelButtons.forEach(function (button) {
        button.addEventListener(
            'click',
            function () {
                showLevel(button.dataset.level);
            }
        );
    });

    levelSections.forEach(function (section) {
        const level = section.dataset.level;

        const checkboxes = Array.from(
            section.querySelectorAll(
                '.exercise-check'
            )
        );

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener(
                'change',
                async function () {
                    const exerciseItem =
                        checkbox.closest(
                            '.exercise-item'
                        );

                    if (exerciseItem) {
                        exerciseItem.classList.toggle(
                            'completed',
                            checkbox.checked
                        );
                    }

                    persistLevelProgress(level);

                    if (level === activeLevel) {
                        updateProgress();
                    }

                    if (!checkbox.checked) {
                        return;
                    }

                    try {
                        const sessionId =
                            await startSession(level);

                        const exerciseNameElement =
                            exerciseItem
                                ? exerciseItem.querySelector(
                                    '.exercise-name'
                                )
                                : null;

                        const exerciseName =
                            exerciseNameElement
                                ? exerciseNameElement
                                    .textContent
                                    .trim()
                                : '';

                        if (!exerciseName) {
                            throw new Error(
                                'Nama exercise tidak ditemukan.'
                            );
                        }

                        await saveExerciseProgress(
                            sessionId,
                            exerciseName
                        );
                    } catch (error) {
                        console.error(
                            'Save Calisthenics progress error:',
                            error
                        );

                        checkbox.checked = false;

                        if (exerciseItem) {
                            exerciseItem.classList.remove(
                                'completed'
                            );
                        }

                        persistLevelProgress(level);
                        updateProgress();

                        alert(
                            'Progress belum berhasil disimpan: '
                            + error.message
                        );
                    }
                }
            );
        });
    });

    finishWorkoutBtn.addEventListener(
        'click',
        async function () {
            const checkboxes =
                getLevelCheckboxes(activeLevel);

            const completedCount =
                getCompletedCount(activeLevel);

            const totalCount =
                checkboxes.length;

            if (completedCount === 0) {
                alert(
                    `Centang minimal satu latihan `
                    + `${levelInformation[activeLevel].title}.`
                );

                return;
            }

            if (completedCount < totalCount) {
                alert(
                    `Selesaikan semua latihan `
                    + `${levelInformation[activeLevel].title} `
                    + `terlebih dahulu.`
                );

                return;
            }

            finishWorkoutBtn.disabled = true;
            finishWorkoutBtn.textContent =
                'Menyimpan...';

            try {
                const sessionId =
                    await startSession(activeLevel);

                const durationMinutes =
                    calculateDuration(activeLevel);

                await finishWorkoutSession(
                    sessionId,
                    durationMinutes
                );

                localStorage.setItem(
                    completedStorageKey(activeLevel),
                    new Date().toISOString()
                );

                resetLevelProgress(activeLevel);
                updateCompletedButtons();

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
                    'Finish Calisthenics error:',
                    error
                );

                alert(
                    'Workout belum berhasil disimpan: '
                    + error.message
                );

                finishWorkoutBtn.disabled = false;
                updateProgress();
            }
        }
    );

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

    levelSections.forEach(function (section) {
        loadLevelProgress(
            section.dataset.level
        );
    });

    updateCompletedButtons();

    const lockedLevel = getLockedLevel();

    if (
        lockedLevel
        && levelInformation[lockedLevel]
    ) {
        activeLevel = lockedLevel;
    }

    showLevel(activeLevel);
});
</script>

</body>
</html>