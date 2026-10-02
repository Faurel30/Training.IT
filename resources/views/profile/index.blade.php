<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Halaman profil dan statistik latihan WorkoutApp."
    >

    <title>Profile | WorkoutApp</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/prof.css') }}"
    >
</head>

<body>

@php
    $displayName =
        $profile->full_name
        ?? $user->name
        ?? $user->username
        ?? 'Workout User';

    $avatarLetter = strtoupper(
        mb_substr($displayName, 0, 1)
    );

    $genderLabel = match ($profile->gender ?? $user->gender) {
        'male' => 'Laki-laki',
        'female' => 'Perempuan',
        default => 'Belum diisi',
    };

    $fitnessLevelLabel = match ($profile->fitness_level) {
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
        default => 'Belum ditentukan',
    };

    $gymCount = $programProgress['gym'] ?? 0;
    $cardioCount = $programProgress['cardio'] ?? 0;
    $calisthenicsCount = $programProgress['calisthenics'] ?? 0;

    $highestProgramCount = max(
        $gymCount,
        $cardioCount,
        $calisthenicsCount,
        1
    );

    $gymPercentage = round(
        ($gymCount / $highestProgramCount) * 100
    );

    $cardioPercentage = round(
        ($cardioCount / $highestProgramCount) * 100
    );

    $calisthenicsPercentage = round(
        ($calisthenicsCount / $highestProgramCount) * 100
    );
@endphp

<div class="profile-page">
    <div class="profile-layout">

        {{-- Sidebar --}}
        <aside class="sidebar">
            <div class="brand">
                <a
                    href="{{ route('landing') }}"
                    class="brand-link"
                    aria-label="Kembali ke halaman utama"
                >
                    <img
                        src="{{ asset('assets/img/logo_baru.png') }}"
                        alt="WorkoutApp Logo"
                    >
                </a>
            </div>

            <nav
                class="nav-links"
                aria-label="Navigasi profil"
            >
                <a
                    href="{{ route('landing') }}"
                    class="nav-btn-solid"
                    aria-label="Home"
                    title="Home"
                >
                    <i class="bx bxs-home"></i>
                </a>

                <a
                    href="{{ route('programs') }}"
                    class="nav-btn-solid"
                    aria-label="Programs"
                    title="Programs"
                >
                    <i class="bx bxs-dashboard"></i>
                </a>

                <a
                    href="{{ route('profile') }}"
                    class="nav-btn-solid active"
                    aria-label="Profile"
                    title="Profile"
                >
                    <i class="bx bxs-user"></i>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                        aria-label="Logout"
                        title="Logout"
                    >
                        <i class="bx bx-log-out"></i>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="profile-content">

            {{-- Notification --}}
            @if (session('success'))
                <div
                    class="profile-alert profile-alert-success"
                    role="status"
                >
                    <i class="bx bx-check-circle"></i>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- Profile Header --}}
            <section class="profile-hero">
                <div class="profile-identity">
                    <div class="profile-avatar">
                        {{ $avatarLetter }}
                    </div>

                    <div class="profile-heading">
                        <span class="eyebrow">
                            Member Profile
                        </span>

                        <h1>
                            {{ $displayName }}
                        </h1>

                        <p>
                            Pantau statistik latihan dan perkembangan
                            kebugaran Anda dalam satu halaman.
                        </p>

                        <div class="member-meta">
                            <span>
                                <i class="bx bx-at"></i>
                                {{ $user->username ?? 'username' }}
                            </span>

                            <span>
                                <i class="bx bx-calendar"></i>
                                Member sejak
                                {{ optional($user->created_at)->format('M Y') ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a
                        href="{{ route('profile.edit') }}"
                        class="primary-action"
                    >
                        <i class="bx bx-edit"></i>
                        Edit Profile
                    </a>

                    <a
                        href="{{ route('programs') }}"
                        class="secondary-action"
                    >
                        <i class="bx bx-dumbbell"></i>
                        Mulai Latihan
                    </a>
                </div>
            </section>

            {{-- Summary Statistics --}}
            <section class="stats-grid">

                <article class="stat-card">
                    <div class="stat-icon">
                        <i class="bx bx-dumbbell"></i>
                    </div>

                    <div>
                        <span class="stat-label">
                            Total Workout
                        </span>

                        <strong>
                            {{ number_format($stats['total_workouts'] ?? 0) }}
                        </strong>

                        <small>
                            Sesi telah diselesaikan
                        </small>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon">
                        <i class="bx bx-time-five"></i>
                    </div>

                    <div>
                        <span class="stat-label">
                            Total Durasi
                        </span>

                        <strong>
                            {{ number_format($stats['total_minutes'] ?? 0) }}
                            <small>menit</small>
                        </strong>

                        <small>
                            Waktu aktif berlatih
                        </small>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon">
                        <i class="bx bx-calendar-check"></i>
                    </div>

                    <div>
                        <span class="stat-label">
                            Bulan Ini
                        </span>

                        <strong>
                            {{ number_format($stats['this_month'] ?? 0) }}
                        </strong>

                        <small>
                            Workout bulan berjalan
                        </small>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon">
                        <i class="bx bx-stopwatch"></i>
                    </div>

                    <div>
                        <span class="stat-label">
                            Rata-rata Durasi
                        </span>

                        <strong>
                            {{ number_format($stats['average_duration'] ?? 0) }}
                            <small>menit</small>
                        </strong>

                        <small>
                            Per sesi workout
                        </small>
                    </div>
                </article>

            </section>

            <div class="dashboard-grid">

                {{-- Profile Completion --}}
                <section class="dashboard-card profile-completion-card">
                    <div class="card-heading">
                        <div>
                            <span class="card-eyebrow">
                                Profile Status
                            </span>

                            <h2>
                                Kelengkapan Profil
                            </h2>
                        </div>

                        <a href="{{ route('profile.edit') }}">
                            Lengkapi
                        </a>
                    </div>

                    <div class="completion-content">
                        <div
                            class="completion-ring"
                            style="--completion: {{ $profileCompletion }}%;"
                        >
                            <div class="completion-ring-inner">
                                <strong>
                                    {{ $profileCompletion }}%
                                </strong>

                                <span>
                                    Complete
                                </span>
                            </div>
                        </div>

                        <div class="completion-details">
                            <p>
                                Profil lengkap membantu WorkoutApp
                                menampilkan informasi kebugaran Anda
                                dengan lebih akurat.
                            </p>

                            <div class="completion-checks">
                                <span class="{{ $profile->full_name ? 'completed' : '' }}">
                                    <i class="bx bx-check"></i>
                                    Nama lengkap
                                </span>

                                <span class="{{ $profile->age ? 'completed' : '' }}">
                                    <i class="bx bx-check"></i>
                                    Umur
                                </span>

                                <span class="{{ $profile->height ? 'completed' : '' }}">
                                    <i class="bx bx-check"></i>
                                    Tinggi badan
                                </span>

                                <span class="{{ $profile->weight ? 'completed' : '' }}">
                                    <i class="bx bx-check"></i>
                                    Berat badan
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- BMI --}}
                <section class="dashboard-card bmi-card">
                    <div class="card-heading">
                        <div>
                            <span class="card-eyebrow">
                                Body Measurement
                            </span>

                            <h2>
                                Body Mass Index
                            </h2>
                        </div>

                        <i class="bx bx-pulse"></i>
                    </div>

                    <div class="bmi-content">
                        <div class="bmi-number">
                            @if ($bmi !== null)
                                {{ number_format($bmi, 1) }}
                            @else
                                —
                            @endif
                        </div>

                        <div class="bmi-description">
                            <span class="bmi-category">
                                {{ $bmiCategory }}
                            </span>

                            <p>
                                BMI dihitung berdasarkan data tinggi
                                dan berat badan pada profil Anda.
                            </p>
                        </div>
                    </div>

                    <div class="bmi-scale">
                        <span>Under</span>
                        <span>Normal</span>
                        <span>Over</span>
                        <span>Obesity</span>
                    </div>
                </section>

            </div>

            {{-- Program Progress --}}
            <section class="dashboard-card program-progress-card">
                <div class="card-heading">
                    <div>
                        <span class="card-eyebrow">
                            Training Summary
                        </span>

                        <h2>
                            Progress Program
                        </h2>
                    </div>

                    <a href="{{ route('programs') }}">
                        Lihat Program
                    </a>
                </div>

                <div class="program-progress-list">

                    <article class="program-progress-item">
                        <div class="program-icon">
                            <i class="bx bx-dumbbell"></i>
                        </div>

                        <div class="program-info">
                            <div class="program-row">
                                <div>
                                    <h3>Gym Training</h3>
                                    <p>Strength and muscle building</p>
                                </div>

                                <strong>
                                    {{ $gymCount }} sesi
                                </strong>
                            </div>

                            <div class="progress-track">
                                <span
                                    style="width: {{ $gymPercentage }}%;"
                                ></span>
                            </div>
                        </div>

                        <a
                            href="{{ route('workout.detail', ['type' => 'gym']) }}"
                            aria-label="Buka Gym Training"
                        >
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    </article>

                    <article class="program-progress-item">
                        <div class="program-icon">
                            <i class="bx bx-run"></i>
                        </div>

                        <div class="program-info">
                            <div class="program-row">
                                <div>
                                    <h3>Cardio Training</h3>
                                    <p>Endurance and cardiovascular health</p>
                                </div>

                                <strong>
                                    {{ $cardioCount }} sesi
                                </strong>
                            </div>

                            <div class="progress-track">
                                <span
                                    style="width: {{ $cardioPercentage }}%;"
                                ></span>
                            </div>
                        </div>

                        <a
                            href="{{ route('workout.detail', ['type' => 'cardio']) }}"
                            aria-label="Buka Cardio Training"
                        >
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    </article>

                    <article class="program-progress-item">
                        <div class="program-icon">
                            <i class="bx bx-body"></i>
                        </div>

                        <div class="program-info">
                            <div class="program-row">
                                <div>
                                    <h3>Calisthenics</h3>
                                    <p>Bodyweight and functional training</p>
                                </div>

                                <strong>
                                    {{ $calisthenicsCount }} sesi
                                </strong>
                            </div>

                            <div class="progress-track">
                                <span
                                    style="width: {{ $calisthenicsPercentage }}%;"
                                ></span>
                            </div>
                        </div>

                        <a
                            href="{{ route('workout.detail', ['type' => 'calisthenic']) }}"
                            aria-label="Buka Calisthenics Training"
                        >
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    </article>

                </div>
            </section>

            <div class="dashboard-grid lower-grid">

                {{-- Personal Information --}}
                <section class="dashboard-card personal-info-card">
                    <div class="card-heading">
                        <div>
                            <span class="card-eyebrow">
                                Account Details
                            </span>

                            <h2>
                                Informasi Pribadi
                            </h2>
                        </div>

                        <a href="{{ route('profile.edit') }}">
                            Edit
                        </a>
                    </div>

                    <div class="personal-info-grid">

                        <div class="personal-info-item">
                            <span>
                                Nama Lengkap
                            </span>

                            <strong>
                                {{ $displayName }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Email
                            </span>

                            <strong>
                                {{ $user->email ?? '-' }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Gender
                            </span>

                            <strong>
                                {{ $genderLabel }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Umur
                            </span>

                            <strong>
                                {{ $profile->age ? $profile->age.' tahun' : '-' }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Tinggi Badan
                            </span>

                            <strong>
                                {{ $profile->height ? number_format($profile->height, 0).' cm' : '-' }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Berat Badan
                            </span>

                            <strong>
                                {{ $profile->weight ? number_format($profile->weight, 1).' kg' : '-' }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Fitness Level
                            </span>

                            <strong>
                                {{ $fitnessLevelLabel }}
                            </strong>
                        </div>

                        <div class="personal-info-item">
                            <span>
                                Username
                            </span>

                            <strong>
                                {{ $user->username ?? '-' }}
                            </strong>
                        </div>

                    </div>

                    <div class="profile-goal">
                        <span>
                            Target / Bio
                        </span>

                        <p>
                            {{ $profile->goal ?: 'Belum ada target latihan yang ditambahkan.' }}
                        </p>
                    </div>
                </section>

                {{-- Recent Workout --}}
                <section class="dashboard-card recent-workout-card">
                    <div class="card-heading">
                        <div>
                            <span class="card-eyebrow">
                                Activity History
                            </span>

                            <h2>
                                Workout Terbaru
                            </h2>
                        </div>

                        <i class="bx bx-history"></i>
                    </div>

                    <div class="recent-workout-list">
                        @forelse ($recentWorkouts as $workout)
                            <article class="recent-workout-item">
                                <div class="recent-workout-icon">
                                    @if ($workout['program_id'] === 1)
                                        <i class="bx bx-dumbbell"></i>
                                    @elseif ($workout['program_id'] === 2)
                                        <i class="bx bx-run"></i>
                                    @elseif ($workout['program_id'] === 3)
                                        <i class="bx bx-body"></i>
                                    @else
                                        <i class="bx bx-pulse"></i>
                                    @endif
                                </div>

                                <div class="recent-workout-info">
                                    <h3>
                                        {{ $workout['program_name'] }}
                                    </h3>

                                    <p>
                                        @if (! empty($workout['date']))
                                            {{ \Illuminate\Support\Carbon::parse($workout['date'])->format('d M Y') }}
                                        @else
                                            Tanggal tidak tersedia
                                        @endif
                                    </p>
                                </div>

                                <div class="recent-workout-duration">
                                    <strong>
                                        {{ $workout['duration_minutes'] }}
                                    </strong>

                                    <span>
                                        menit
                                    </span>
                                </div>
                            </article>
                        @empty
                            <div class="empty-workout">
                                <i class="bx bx-dumbbell"></i>

                                <h3>
                                    Belum Ada Workout
                                </h3>

                                <p>
                                    Selesaikan program latihan pertama
                                    untuk menampilkan riwayat di sini.
                                </p>

                                <a href="{{ route('programs') }}">
                                    Mulai Workout
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>

        </main>
    </div>
</div>

</body>
</html>