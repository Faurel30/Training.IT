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

    <title>Gym Training Program</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/gym_training.css') }}?v=20260715-3"
    >

    <style>
        .day-selector {
            max-width: 980px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin: 0 auto 28px;
        }

        .day-button {
            position: relative;
            min-height: 76px;
            padding: 14px 18px;
            border: 1px solid rgba(255, 90, 90, 0.25);
            border-radius: 17px;
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

        .day-button:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 90, 90, 0.55);
            background: rgba(255, 90, 90, 0.1);
        }

        .day-button.active {
            border-color: rgba(255, 90, 90, 0.8);
            background:
                linear-gradient(
                    135deg,
                    rgba(255, 65, 65, 0.25),
                    rgba(100, 0, 0, 0.22)
                );
            box-shadow: 0 12px 30px rgba(255, 50, 50, 0.16);
        }

        .day-button.completed::after {
            content: "✓";
            position: absolute;
            top: 12px;
            right: 14px;
            width: 24px;
            height: 24px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            background: #39a852;
        }

        .day-button strong {
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
            font-size: 15px;
        }

        .day-button span {
            color: rgba(255, 255, 255, 0.68);
            font-size: 11px;
        }

        .workout-day[hidden] {
            display: none !important;
        }

        .active-day-badge {
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
            .day-selector {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .day-button {
                min-height: 68px;
            }
        }
    </style>
</head>

<body>

@php
    $profileCompleted = $profileCompleted ?? false;
@endphp

<div class="container">

    <header class="header">
        <h1>GYM TRAINING PROGRAM</h1>

        <p>
            Program latihan gym terstruktur untuk membangun
            kekuatan dan massa otot.
        </p>
    </header>

    {{-- Pilihan hari latihan --}}
    <section
        class="day-selector"
        aria-label="Pilih hari latihan"
    >
        <button
            type="button"
            class="day-button active"
            data-day="1"
        >
            <strong>Day 1</strong>
            <span>Upper Body · 5 latihan</span>
        </button>

        <button
            type="button"
            class="day-button"
            data-day="2"
        >
            <strong>Day 2</strong>
            <span>Lower Body · 4 latihan</span>
        </button>

        <button
            type="button"
            class="day-button"
            data-day="3"
        >
            <strong>Day 3</strong>
            <span>Full Body · 3 latihan</span>
        </button>
    </section>

    {{-- Tracker hari aktif --}}
    <section
        class="tracker-card"
        aria-label="Workout progress tracker"
    >
        <div class="tracker-top">
            <div>
                <span
                    class="active-day-badge"
                    id="activeDayBadge"
                >
                    Day 1 · Upper Body
                </span>

                <span class="tracker-label">
                    Workout Progress
                </span>

                <h2 id="progressText">
                    0 of 5 exercises completed
                </h2>
            </div>

            <button
                class="finish-btn"
                id="finishWorkoutBtn"
                type="button"
            >
                Finish Day 1
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
            Centang semua latihan pada hari yang sedang aktif.
            Progress disimpan otomatis.
        </p>
    </section>

    {{-- Day 1 --}}
    <section
        class="workout-section workout-day"
        data-day="1"
    >
        <h2>Upper Body</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >
                <span class="exercise-name">
                    Bench Press
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
                    Lat Pulldown
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
                    Shoulder Press
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
                    Bicep Curl
                </span>
                <span class="exercise-sets">
                    3x12
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >
                <span class="exercise-name">
                    Tricep Pushdown
                </span>
                <span class="exercise-sets">
                    3x12
                </span>
            </label>

        </div>
    </section>

    {{-- Day 2 --}}
    <section
        class="workout-section workout-day"
        data-day="2"
        hidden
    >
        <h2>Lower Body</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >
                <span class="exercise-name">
                    Squat
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
                    Leg Press
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
                    Leg Curl
                </span>
                <span class="exercise-sets">
                    3x12
                </span>
            </label>

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >
                <span class="exercise-name">
                    Calf Raise
                </span>
                <span class="exercise-sets">
                    3x15
                </span>
            </label>

        </div>
    </section>

    {{-- Day 3 --}}
    <section
        class="workout-section workout-day"
        data-day="3"
        hidden
    >
        <h2>Full Body</h2>

        <div class="exercise-list">

            <label class="exercise-item">
                <input
                    type="checkbox"
                    class="exercise-check"
                >
                <span class="exercise-name">
                    Deadlift
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
                    Push Up
                </span>
                <span class="exercise-sets">
                    3x max
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

    <div class="note-section">
        <h3>Catatan Penting:</h3>

        <p>
            Pilih satu hari latihan dan selesaikan seluruh latihan
            pada hari tersebut.
        </p>

        <ul>
            <li>
                <strong>Day 1:</strong>
                Upper Body
            </li>

            <li>
                <strong>Day 2:</strong>
                Lower Body
            </li>

            <li>
                <strong>Day 3:</strong>
                Full Body
            </li>

            <li>
                <strong>Day 4:</strong>
                Rest atau mengulang Upper Body jika kondisi tubuh memungkinkan.
            </li>
        </ul>

        <p>
            Jangan menyelesaikan seluruh hari dalam satu waktu.
            Berikan waktu pemulihan yang cukup bagi otot.
        </p>
    </div>

</div>

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
                kebugaran tampil lebih lengkap.
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
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    const dayButtons = Array.from(
        document.querySelectorAll('.day-button')
    );

    const daySections = Array.from(
        document.querySelectorAll('.workout-day')
    );

    const progressText =
        document.getElementById('progressText');

    const progressFill =
        document.getElementById('progressFill');

    const activeDayBadge =
        document.getElementById('activeDayBadge');

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

    const activeDayStorageKey =
        'workoutActiveDay:gym';

    const pendingSessionRequests = {};

    const dayInformation = {
        1: {
            label: 'Day 1 · Upper Body'
        },
        2: {
            label: 'Day 2 · Lower Body'
        },
        3: {
            label: 'Day 3 · Full Body'
        }
    };

    let activeDay =
        localStorage.getItem(activeDayStorageKey) || '1';

    if (!dayInformation[activeDay]) {
        activeDay = '1';
    }

    function progressStorageKey(day) {
        return `workoutProgress:gym:day:${day}`;
    }

    function sessionStorageKey(day) {
        return `workoutSessionId:gym:day:${day}`;
    }

    function startTimeStorageKey(day) {
        return `workoutStartedAt:gym:day:${day}`;
    }

    function completedStorageKey(day) {
        return `workoutCompleted:gym:day:${day}`;
    }

    function getDaySection(day) {
        return document.querySelector(
            `.workout-day[data-day="${day}"]`
        );
    }

    function getDayCheckboxes(day) {
        const section = getDaySection(day);

        if (!section) {
            return [];
        }

        return Array.from(
            section.querySelectorAll('.exercise-check')
        );
    }

    function loadDayProgress(day) {
        const checkboxes = getDayCheckboxes(day);

        let savedState = [];

        try {
            savedState = JSON.parse(
                localStorage.getItem(
                    progressStorageKey(day)
                ) || '[]'
            );
        } catch (error) {
            savedState = [];
        }

        checkboxes.forEach(function (checkbox, index) {
            checkbox.checked =
                Boolean(savedState[index]);

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

    function persistDayProgress(day) {
        const checkboxes = getDayCheckboxes(day);

        localStorage.setItem(
            progressStorageKey(day),
            JSON.stringify(
                checkboxes.map(function (checkbox) {
                    return checkbox.checked;
                })
            )
        );
    }

    function getCompletedCount(day) {
        return getDayCheckboxes(day).filter(
            function (checkbox) {
                return checkbox.checked;
            }
        ).length;
    }

    function updateCompletedDayButtons() {
        dayButtons.forEach(function (button) {
            const day = button.dataset.day;

            const completed =
                Boolean(
                    localStorage.getItem(
                        completedStorageKey(day)
                    )
                );

            button.classList.toggle(
                'completed',
                completed
            );
        });
    }

    function showDay(day) {
        activeDay = String(day);

        localStorage.setItem(
            activeDayStorageKey,
            activeDay
        );

        dayButtons.forEach(function (button) {
            button.classList.toggle(
                'active',
                button.dataset.day === activeDay
            );
        });

        daySections.forEach(function (section) {
            section.hidden =
                section.dataset.day !== activeDay;
        });

        updateProgress();
    }

    function updateProgress() {
        const checkboxes =
            getDayCheckboxes(activeDay);

        const completedCount =
            getCompletedCount(activeDay);

        const totalCount =
            checkboxes.length;

        const percentage =
            totalCount === 0
                ? 0
                : Math.round(
                    (completedCount / totalCount) * 100
                );

        progressText.textContent =
            `${completedCount} of ${totalCount} exercises completed`;

        progressFill.style.width =
            `${percentage}%`;

        activeDayBadge.textContent =
            dayInformation[activeDay].label;

        finishWorkoutBtn.textContent =
            completedCount === totalCount
            && totalCount > 0
                ? `Finish Day ${activeDay}`
                : `Finish Day ${activeDay}`;
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
                'Terjadi kesalahan pada server.'
            );
        }

        return json;
    }

    async function startSession(day) {
        if (pendingSessionRequests[day]) {
            return pendingSessionRequests[day];
        }

        const existingSessionId =
            localStorage.getItem(
                sessionStorageKey(day)
            );

        if (existingSessionId) {
            return existingSessionId;
        }

        pendingSessionRequests[day] =
            requestJson(
                '{{ route('workout.startSession') }}',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken
                    },
                    body: JSON.stringify({
                        program_id: 1
                    })
                }
            )
            .then(function (json) {
                const sessionId =
                    String(json.session_id);

                localStorage.setItem(
                    sessionStorageKey(day),
                    sessionId
                );

                if (
                    !localStorage.getItem(
                        startTimeStorageKey(day)
                    )
                ) {
                    localStorage.setItem(
                        startTimeStorageKey(day),
                        String(Date.now())
                    );
                }

                return sessionId;
            })
            .finally(function () {
                delete pendingSessionRequests[day];
            });

        return pendingSessionRequests[day];
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
                    'Content-Type':
                        'application/json',

                    'Accept':
                        'application/json',

                    'X-CSRF-TOKEN':
                        csrfToken
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
                    'Content-Type':
                        'application/json',

                    'Accept':
                        'application/json',

                    'X-CSRF-TOKEN':
                        csrfToken
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    duration_minutes: durationMinutes
                })
            }
        );
    }

    function calculateDuration(day) {
        const startedAt = Number(
            localStorage.getItem(
                startTimeStorageKey(day)
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

    function resetDayProgress(day) {
        const checkboxes =
            getDayCheckboxes(day);

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
            progressStorageKey(day)
        );

        localStorage.removeItem(
            sessionStorageKey(day)
        );

        localStorage.removeItem(
            startTimeStorageKey(day)
        );

        updateProgress();
    }

    dayButtons.forEach(function (button) {
        button.addEventListener(
            'click',
            function () {
                showDay(button.dataset.day);
            }
        );
    });

    daySections.forEach(function (section) {
        const day = section.dataset.day;

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

                    persistDayProgress(day);

                    if (day === activeDay) {
                        updateProgress();
                    }

                    if (!checkbox.checked) {
                        return;
                    }

                    try {
                        const sessionId =
                            await startSession(day);

                        const exerciseName =
                            checkbox
                                .closest(
                                    '.exercise-item'
                                )
                                .querySelector(
                                    '.exercise-name'
                                )
                                .textContent
                                .trim();

                        await saveExerciseProgress(
                            sessionId,
                            exerciseName
                        );
                    } catch (error) {
                        console.error(error);

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
                getDayCheckboxes(activeDay);

            const completedCount =
                getCompletedCount(activeDay);

            const totalCount =
                checkboxes.length;

            if (completedCount === 0) {
                alert(
                    `Centang minimal satu latihan pada Day ${activeDay}.`
                );

                return;
            }

            if (completedCount < totalCount) {
                alert(
                    `Selesaikan semua latihan Day ${activeDay} terlebih dahulu.`
                );

                return;
            }

            finishWorkoutBtn.disabled = true;
            finishWorkoutBtn.textContent =
                'Menyimpan...';

            try {
                const sessionId =
                    await startSession(activeDay);

                const durationMinutes =
                    calculateDuration(activeDay);

                await finishWorkoutSession(
                    sessionId,
                    durationMinutes
                );

                localStorage.setItem(
                    completedStorageKey(activeDay),
                    new Date().toISOString()
                );

                resetDayProgress(activeDay);
                updateCompletedDayButtons();

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
                console.error(error);

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
        completionModal.classList.add('visible');

        completionModal.setAttribute(
            'aria-hidden',
            'false'
        );
    }

    function hideModal() {
        completionModal.classList.remove('visible');

        completionModal.setAttribute(
            'aria-hidden',
            'true'
        );
    }

    daySections.forEach(function (section) {
        loadDayProgress(section.dataset.day);
    });

    updateCompletedDayButtons();
    showDay(activeDay);
});
</script>

</body>
</html>