<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <link rel="stylesheet" href="{{ asset('assets/css/forgot_style.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

<div class="wrapper">
    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <h1>Forgot Password</h1>

        <p class="subtitle">
            Masukkan email akun untuk menerima link reset password.
        </p>

        @if (session('success'))
            <div class="success-message" style="display:block;">
                {{ session('success') }}
            </div>
        @endif

        @error('email')
            <div class="error-message" style="display:block;">
                {{ $message }}
            </div>
        @enderror

        <div class="input-box">
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Email"
                required
            >
            <i class="bx bxs-envelope"></i>
        </div>

        <button type="submit" class="btn">
            Send Reset Link
        </button>

        <div class="back-link">
            <p>
                <a href="{{ route('login') }}">Kembali ke Login</a>
            </p>
        </div>
    </form>
</div>

</body>
</html>