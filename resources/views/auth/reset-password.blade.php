<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    <link rel="stylesheet" href="{{ asset('assets/css/forgot_style.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

<div class="wrapper">
    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <h1>Reset Password</h1>

        @if ($errors->any())
            <div class="error-message" style="display:block;">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="input-box">
            <input
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                placeholder="Email"
                required
            >
            <i class="bx bxs-envelope"></i>
        </div>

        <div class="input-box">
            <input
                type="password"
                name="password"
                placeholder="Password Baru"
                required
            >
            <i class="bx bxs-lock-alt"></i>
        </div>

        <div class="input-box">
            <input
                type="password"
                name="password_confirmation"
                placeholder="Konfirmasi Password"
                required
            >
            <i class="bx bxs-lock-alt"></i>
        </div>

        <button type="submit" class="btn">
            Reset Password
        </button>
    </form>
</div>

</body>
</html> 