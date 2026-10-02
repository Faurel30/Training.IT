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
        content="Masuk ke WorkoutApp untuk melanjutkan program latihan."
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/sign_in.css') }}"
    >

    <link
        rel="stylesheet"
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    >

    <title>Login | WorkoutApp</title>
</head>

<body>

<main class="wrapper">
    <form
        id="loginForm"
        action="{{ route('login.process') }}"
        method="POST"
        autocomplete="off"
    >
        @csrf

        <h1>Login</h1>

        {{-- Pesan berhasil setelah register, logout, atau reset password --}}
        @if (session('success'))
            <div
                role="status"
                style="
                    display: block;
                    margin-bottom: 15px;
                    padding: 10px 12px;
                    border: 1px solid rgba(76, 175, 80, 0.45);
                    border-radius: 8px;
                    background: rgba(76, 175, 80, 0.12);
                    color: #8fe394;
                    font-size: 13px;
                    line-height: 1.5;
                    text-align: center;
                "
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error login atau validasi --}}
        @if ($errors->any())
            <div
                role="alert"
                style="
                    display: block;
                    margin-bottom: 15px;
                    padding: 10px 12px;
                    border: 1px solid rgba(255, 77, 77, 0.45);
                    border-radius: 8px;
                    background: rgba(255, 77, 77, 0.12);
                    color: #ff9292;
                    font-size: 13px;
                    line-height: 1.5;
                    text-align: center;
                "
            >
                {{ $errors->first() }}
            </div>
        @endif

        <div class="input-box">
            <input
                type="text"
                name="username"
                id="username"
                value="{{ old('username') }}"
                placeholder="Username"
                autocomplete="off"
                maxlength="50"
                aria-label="Username"
                required
                autofocus
            >

            <i
                class="bx bxs-user"
                aria-hidden="true"
            ></i>
        </div>

        <div class="input-box">
            <input
                type="password"
                name="password"
                id="password"
                value=""
                placeholder="Password"
                autocomplete="new-password"
                aria-label="Password"
                required
            >

            <i
                class="bx bxs-lock-alt"
                aria-hidden="true"
            ></i>
        </div>

        <div class="remember-forgot">
            <label for="remember">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    value="1"
                    {{ old('remember') ? 'checked' : '' }}
                >

                Remember me
            </label>

            <a
                href="{{ route('password.request') }}"
                title="Reset password akun"
            >
                Forgot Password?
            </a>
        </div>

        <button
            type="submit"
            class="btn"
        >
            Login
        </button>

        <div class="register-link">
            <p>
                Belum memiliki akun?

                <a href="{{ route('register') }}">
                    Register
                </a>
            </p>
        </div>
    </form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');

        if (passwordInput) {
            passwordInput.value = '';
        }
    });

    window.addEventListener('pageshow', function () {
        const passwordInput = document.getElementById('password');

        if (passwordInput) {
            passwordInput.value = '';
        }
    });
</script>

</body>
</html>