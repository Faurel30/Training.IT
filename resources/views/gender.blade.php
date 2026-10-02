<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;600;900&display=swap" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('assets/css/gender_page.css') }}">

    <title>Gender Selection</title>
</head>
<body>

<div class="gender-wrapper">
    <form id="genderForm" action="{{ route('gender.save') }}" method="POST">
        @csrf <div class="gender-card">
            <h1>Gender Selection</h1>
            <p>Pilih jenis kelamin Anda agar program dapat disesuaikan dengan lebih tepat.</p>

            @if ($errors->any())
                <div style="color: #ff4d4d; margin-bottom: 15px; font-size: 14px; text-align: center;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="gender-options">
                <label class="gender-option">
                    <input type="radio" name="gender" value="male" required>
                    <div class="option-icon">
                        <img src="{{ asset('assets/img/male.png') }}" alt="Male" class="gender-img">
                    </div>
                    <span>Laki-laki</span>
                </label>
                
                <label class="gender-option">
                    <input type="radio" name="gender" value="female" required>
                    <div class="option-icon">
                        <img src="{{ asset('assets/img/female.png') }}" alt="Female" class="gender-img">
                    </div>
                    <span>Perempuan</span>
                </label>
            </div>

            <button type="submit" class="btn">Continue</button>
            <p class="note">Setelah memilih, Anda akan diarahkan ke halaman selanjutnya.</p>
        </div>
    </form>
</div>

</body>
</html>