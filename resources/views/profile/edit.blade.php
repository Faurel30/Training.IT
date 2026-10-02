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
        content="Halaman pengaturan profil pengguna WorkoutApp."
    >

    <title>Edit Profile | WorkoutApp</title>

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
    href="{{ asset('assets/css/prof.css') }}?v=20260715-2"
    >

    <link
    rel="stylesheet"
    href="{{ asset('assets/css/edit.css') }}?v=20260715-2"
    >
</head>

<body>

@php
    $displayName =
        $profile->full_name
        ?? $user->name
        ?? $user->username
        ?? '';

    $genderValue =
        $profile->gender
        ?? $user->gender
        ?? null;

    $genderLabel = match ($genderValue) {
        'male' => 'Laki-laki',
        'female' => 'Perempuan',
        default => 'Belum dipilih',
    };

    $fitnessLevel =
        old(
            'fitness_level',
            $profile->fitness_level ?? 'beginner'
        );
@endphp

<div class="profile-page edit-page">
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
        <main class="edit-profile-content">

            <section class="edit-profile-card">

                {{-- Header --}}
                <div class="edit-header">
                    <div>
                        <span class="header-kicker">
                            Profile Settings
                        </span>

                        <h1>
                            Edit Profile
                        </h1>

                        <p>
                            Lengkapi data pribadi dan informasi kebugaran
                            untuk membantu memantau perkembangan latihan.
                        </p>
                    </div>

                    <a
                        class="back-btn"
                        href="{{ route('profile') }}"
                    >
                        <i class="bx bx-arrow-back"></i>
                        Kembali ke Profile
                    </a>
                </div>

                {{-- Error Summary --}}
                @if ($errors->any())
                    <div
                        class="form-alert form-alert-error"
                        role="alert"
                    >
                        <i class="bx bx-error-circle"></i>

                        <div>
                            <strong>
                                Data belum dapat disimpan.
                            </strong>

                            <p>
                                Silakan periksa kembali kolom yang ditandai.
                            </p>
                        </div>
                    </div>
                @endif

                <form
                    class="edit-form"
                    id="editForm"
                    action="{{ route('profile.update') }}"
                    method="POST"
                    autocomplete="on"
                >
                    @csrf

                    {{-- Personal Information --}}
                    <section class="form-section">
                        <div class="form-section-heading">
                            <div class="form-section-icon">
                                <i class="bx bxs-user-detail"></i>
                            </div>

                            <div>
                                <span>
                                    Account Information
                                </span>

                                <h2>
                                    Informasi Pribadi
                                </h2>

                                <p>
                                    Data utama yang digunakan pada profil
                                    WorkoutApp.
                                </p>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- Full Name --}}
                            <div class="form-group">
                                <label for="fullName">
                                    Nama Lengkap
                                </label>

                                <div class="input-wrapper">
                                    <i class="bx bxs-user"></i>

                                    <input
                                        type="text"
                                        id="fullName"
                                        name="name"
                                        value="{{ old('name', $displayName) }}"
                                        placeholder="Masukkan nama lengkap"
                                        autocomplete="name"
                                        minlength="2"
                                        maxlength="100"
                                        class="{{ $errors->has('name') ? 'input-error' : '' }}"
                                        required
                                    >
                                </div>

                                @error('name')
                                    <p class="field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email">
                                    Email
                                </label>

                                <div class="input-wrapper">
                                    <i class="bx bxs-envelope"></i>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email ?? '') }}"
                                        placeholder="Masukkan email"
                                        autocomplete="email"
                                        maxlength="255"
                                        class="{{ $errors->has('email') ? 'input-error' : '' }}"
                                        required
                                    >
                                </div>

                                @error('email')
                                    <p class="field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Username --}}
                            <div class="form-group">
                                <label for="username">
                                    Username
                                </label>

                                <div class="input-wrapper input-readonly">
                                    <i class="bx bx-at"></i>

                                    <input
                                        type="text"
                                        id="username"
                                        value="{{ $user->username ?? '-' }}"
                                        readonly
                                    >
                                </div>

                                <p class="field-help">
                                    Username tidak dapat diubah dari halaman ini.
                                </p>
                            </div>

                            {{-- Gender --}}
                            <div class="form-group">
                                <label for="gender">
                                    Gender
                                </label>

                                <div class="input-wrapper input-readonly">
                                    <i class="bx bx-male-female"></i>

                                    <input
                                        type="text"
                                        id="gender"
                                        value="{{ $genderLabel }}"
                                        readonly
                                    >
                                </div>

                                <p class="field-help">
                                    Gender mengikuti pilihan awal akun.
                                </p>
                            </div>

                        </div>
                    </section>

                    {{-- Body Measurement --}}
                    <section class="form-section">
                        <div class="form-section-heading">
                            <div class="form-section-icon">
                                <i class="bx bx-pulse"></i>
                            </div>

                            <div>
                                <span>
                                    Body Measurement
                                </span>

                                <h2>
                                    Data Tubuh
                                </h2>

                                <p>
                                    Data ini digunakan untuk menghitung BMI
                                    dan ringkasan kebugaran.
                                </p>
                            </div>
                        </div>

                        <div class="form-grid form-grid-three">

                            {{-- Age --}}
                            <div class="form-group">
                                <label for="age">
                                    Umur
                                </label>

                                <div class="input-wrapper">
                                    <i class="bx bx-calendar"></i>

                                    <input
                                        type="number"
                                        id="age"
                                        name="age"
                                        value="{{ old('age', $profile->age ?? '') }}"
                                        placeholder="Contoh: 22"
                                        min="10"
                                        max="100"
                                        class="{{ $errors->has('age') ? 'input-error' : '' }}"
                                        required
                                    >
                                </div>

                                <span class="input-unit">
                                    Tahun
                                </span>

                                @error('age')
                                    <p class="field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Height --}}
                            <div class="form-group">
                                <label for="height">
                                    Tinggi Badan
                                </label>

                                <div class="input-wrapper">
                                    <i class="bx bx-ruler"></i>

                                    <input
                                        type="number"
                                        id="height"
                                        name="height"
                                        value="{{ old('height', $profile->height ?? '') }}"
                                        placeholder="Contoh: 170"
                                        min="80"
                                        max="250"
                                        step="0.1"
                                        class="{{ $errors->has('height') ? 'input-error' : '' }}"
                                        required
                                    >
                                </div>

                                <span class="input-unit">
                                    Centimeter
                                </span>

                                @error('height')
                                    <p class="field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Weight --}}
                            <div class="form-group">
                                <label for="weight">
                                    Berat Badan
                                </label>

                                <div class="input-wrapper">
                                    <i class="bx bx-dumbbell"></i>

                                    <input
                                        type="number"
                                        id="weight"
                                        name="weight"
                                        value="{{ old('weight', $profile->weight ?? '') }}"
                                        placeholder="Contoh: 70"
                                        min="20"
                                        max="400"
                                        step="0.1"
                                        class="{{ $errors->has('weight') ? 'input-error' : '' }}"
                                        required
                                    >
                                </div>

                                <span class="input-unit">
                                    Kilogram
                                </span>

                                @error('weight')
                                    <p class="field-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </section>

                    {{-- Fitness Information --}}
                    <section class="form-section">
                        <div class="form-section-heading">
                            <div class="form-section-icon">
                                <i class="bx bx-run"></i>
                            </div>

                            <div>
                                <span>
                                    Fitness Preferences
                                </span>

                                <h2>
                                    Informasi Kebugaran
                                </h2>

                                <p>
                                    Pilih tingkat kebugaran dan tuliskan
                                    target latihan Anda.
                                </p>
                            </div>
                        </div>

                        {{-- Fitness Level --}}
                        <div class="form-group">
                            <label>
                                Fitness Level
                            </label>

                            <div class="fitness-level-options">

                                <label class="fitness-option">
                                    <input
                                        type="radio"
                                        name="fitness_level"
                                        value="beginner"
                                        {{ $fitnessLevel === 'beginner' ? 'checked' : '' }}
                                    >

                                    <span class="fitness-option-card">
                                        <i class="bx bx-walk"></i>

                                        <strong>
                                            Beginner
                                        </strong>

                                        <small>
                                            Baru memulai latihan
                                        </small>
                                    </span>
                                </label>

                                <label class="fitness-option">
                                    <input
                                        type="radio"
                                        name="fitness_level"
                                        value="intermediate"
                                        {{ $fitnessLevel === 'intermediate' ? 'checked' : '' }}
                                    >

                                    <span class="fitness-option-card">
                                        <i class="bx bx-run"></i>

                                        <strong>
                                            Intermediate
                                        </strong>

                                        <small>
                                            Sudah rutin berlatih
                                        </small>
                                    </span>
                                </label>

                                <label class="fitness-option">
                                    <input
                                        type="radio"
                                        name="fitness_level"
                                        value="advanced"
                                        {{ $fitnessLevel === 'advanced' ? 'checked' : '' }}
                                    >

                                    <span class="fitness-option-card">
                                        <i class="bx bx-trophy"></i>

                                        <strong>
                                            Advanced
                                        </strong>

                                        <small>
                                            Berpengalaman dan intensif
                                        </small>
                                    </span>
                                </label>

                            </div>

                            @error('fitness_level')
                                <p class="field-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Goal / Bio --}}
                        <div class="form-group goal-group">
                            <label for="userBio">
                                Target dan Bio Latihan
                            </label>

                            <div class="textarea-wrapper">
                                <textarea
                                    id="userBio"
                                    name="userBio"
                                    rows="6"
                                    maxlength="1000"
                                    placeholder="Contoh: Saya ingin meningkatkan kekuatan, membentuk massa otot, dan menjaga konsistensi latihan."
                                    class="{{ $errors->has('userBio') ? 'input-error' : '' }}"
                                >{{ old('userBio', $profile->goal ?? '') }}</textarea>
                            </div>

                            <div class="textarea-meta">
                                <span>
                                    Ceritakan target kebugaran Anda.
                                </span>

                                <span id="bioCounter">
                                    0 / 1000
                                </span>
                            </div>

                            @error('userBio')
                                <p class="field-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </section>

                    {{-- Form Actions --}}
                    <div class="form-actions">
                        <a
                            href="{{ route('profile') }}"
                            class="cancel-btn"
                        >
                            <i class="bx bx-x"></i>
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="save-btn"
                            id="saveButton"
                        >
                            <i class="bx bx-save"></i>

                            <span id="saveButtonText">
                                Simpan Perubahan
                            </span>
                        </button>
                    </div>

                </form>
            </section>

        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('editForm');
        const saveButton = document.getElementById('saveButton');
        const saveButtonText = document.getElementById('saveButtonText');

        const bio = document.getElementById('userBio');
        const bioCounter = document.getElementById('bioCounter');

        function updateBioCounter() {
            if (!bio || !bioCounter) {
                return;
            }

            bioCounter.textContent =
                `${bio.value.length} / 1000`;
        }

        updateBioCounter();

        if (bio) {
            bio.addEventListener('input', updateBioCounter);
        }

        if (form) {
            form.addEventListener('submit', function () {
                saveButton.disabled = true;
                saveButtonText.textContent = 'Menyimpan...';
            });
        }
    });
</script>

</body>
</html>