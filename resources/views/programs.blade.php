<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;600;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/programs_page.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="{{ route('landing') }}" class="logo-link">
                <img src="{{ asset('assets/img/logo_baru.png') }}" alt="Training.IT" class="logo-img">
            </a>
        </div>

        <ul class="menu">
            <li><a href="{{ route('landing') }}">HOME</a></li>
            <li><a href="{{ route('programs') }}">PROGRAMS</a></li>
            <li><a href="{{ route('about') }}">ABOUT US</a></li>
        </ul>

        <div class="nav-actions">
            @auth
                <div class="nav-user-actions">
                    <a href="{{ route('profile') }}" class="profile-btn">PROFILE</a>
                    <form action="{{ route('logout') }}" method="POST" class="nav-logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">LOGOUT</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="login-btn">LOGIN</a>
            @endauth
        </div>
    </nav>

    <section class="programs-section">
        <div class="programs-header">
            <h1>OUR FITNESS PROGRAMS</h1>
            <p>Choose the perfect program that fits your lifestyle and goals</p>
        </div>

        @if (session('error'))
            <div style="color: #ff4d4d; text-align: center; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="programs-grid">
            <article class="program-card">
                <h2>Gym Training</h2>
                <p>Build strength and muscle with our comprehensive gym training programs using weights and equipment.</p>
                <ul>
                    <li>Personalized workout plans</li>
                    <li>Equipment guidance</li>
                    <li>Progressive overload</li>
                    <li>Form correction</li>
                </ul>
                <form action="{{ route('programs.select') }}" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="1">
                    <input type="hidden" name="type" value="gym"> <button type="submit" class="program-btn">Pilih Gym</button>
                </form>
            </article>

            <article class="program-card">
                <h2>Calisthenics Training</h2>
                <p>Master bodyweight exercises to build functional strength, flexibility, and overall fitness.</p>
                <ul>
                    <li>Bodyweight exercises</li>
                    <li>Progressive difficulty</li>
                    <li>No equipment needed</li>
                    <li>Functional movement</li>
                </ul>
                <form action="{{ route('programs.select') }}" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="3">
                    <input type="hidden" name="type" value="calisthenic">
                    <button type="submit" class="program-btn">Pilih Calisthenics</button>
                </form>
            </article>

            <article class="program-card">
                <h2>Cardio and Endurance Training</h2>
                <p>Improve cardiovascular health and build endurance with our dynamic cardio programs.</p>
                <ul>
                    <li>Running and jogging</li>
                    <li>HIIT sessions</li>
                    <li>Endurance building</li>
                    <li>Heart rate monitoring</li>
                </ul>
                <form action="{{ route('programs.select') }}" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="2">
                    <input type="hidden" name="type" value="cardio">
                    <button type="submit" class="program-btn">Pilih Cardio</button>
                </form>
            </article>
        </div>
    </section>

    </body>
</html>