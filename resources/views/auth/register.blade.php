<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('assets/css/sign_up.css') }}">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Register</title>
</head>
<body>

<div class="wrapper">
    <form id="registerForm" action="{{ url('/register') }}" method="POST">
        @csrf <h1>Sign Up</h1>

        @if ($errors->any())
            <div style="color: #ff4d4d; margin-bottom: 15px; font-size: 14px; text-align: center;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="input-box">
            <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
            <i class='bx bxs-envelope'></i>
        </div>

        <div class="input-box">
            <input type="text" name="username" id="username" placeholder="Username" value="{{ old('username') }}" required>
            <i class='bx bxs-user'></i>
        </div>

        <div class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt'></i>
        </div>

        <div class="input-box">
            <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Confirm Password" required>
            <i class='bx bxs-lock-alt'></i>
        </div>

        <div class="remember-forgot">
            <label><input type="checkbox" name="agreeTerms" id="agreeTerms" required> I agree to the terms</label>
        </div>

        <button type="submit" class="btn">Sign Up</button>

        <div class="register-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </form>
</div>

</body>
</html>