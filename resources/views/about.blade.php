<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;600;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/learn_more.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site-footer.css') }}">

    <title>Learn More - Fitness Programs</title>
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <a href="{{ route('landing') }}" class="logo-link">
            <img src="{{ asset('assets/img/logo_baru.png') }}" alt="Training.IT Logo" class="logo-img">
        </a>
    </div>

    <ul class="menu">
        <li><a href="{{ route('landing') }}">HOME</a></li>
        <li><a href="{{ route('programs') }}">PROGRAMS</a></li>
        <li><a href="#about">ABOUT US</a></li>
    </ul>

    <div class="nav-actions" id="navActions">
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

<section class="hero-section" id="about">
    <div class="hero-content">
        <h1>DISCOVER YOUR FITNESS JOURNEY</h1>
        <p>Transform your body and mind with three focused training paths built to support every stage of your fitness growth.</p>
        <div class="hero-stats">
            <div class="stat">
                <h3>3</h3>
                <p>Core Programs</p>
            </div>
            <div class="stat">
                <h3>4.9/5</h3>
                <p>Member Satisfaction</p>
            </div>
            <div class="stat">
                <h3>100%</h3>
                <p>Progress Focused</p>
            </div>
        </div>
    </div>
    <div class="hero-bg"></div>
</section>

<section id="programs" class="programs-section">
    <div class="container">
        <h2>OUR FITNESS PROGRAMS</h2>
        <p class="section-subtitle">Choose the perfect program that fits your lifestyle and goals</p>

        <div class="programs-grid">
            <div class="program-card">
                <div class="program-icon">
                    <i class='bx bx-dumbbell'></i>
                </div>
                <h3>Gym Training</h3>
                <p>Build strength and muscle with our comprehensive gym training programs using weights and equipment.</p>
                <ul class="program-features">
                    <li>Personalized workout plans</li>
                    <li>Equipment guidance</li>
                    <li>Progressive overload</li>
                    <li>Form correction</li>
                </ul>
            </div>

            <div class="program-card">
                <div class="program-icon">
                    <i class='bx bx-body'></i>
                </div>
                <h3>Calisthenics Training</h3>
                <p>Master bodyweight exercises to build functional strength, flexibility, and overall fitness.</p>
                <ul class="program-features">
                    <li>Bodyweight exercises</li>
                    <li>Progressive difficulty</li>
                    <li>No equipment needed</li>
                    <li>Functional movement</li>
                </ul>
            </div>

            <div class="program-card">
                <div class="program-icon">
                    <i class='bx bx-heart'></i>
                </div>
                <h3>Cardio and Endurance Training</h3>
                <p>Improve cardiovascular health and build endurance with our dynamic cardio programs.</p>
                <ul class="program-features">
                    <li>Running and jogging</li>
                    <li>HIIT sessions</li>
                    <li>Endurance building</li>
                    <li>Heart rate monitoring</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="why-choose-section">
    <div class="container">
        <h2>WHY CHOOSE TRAINING.IT?</h2>
        <div class="why-choose-grid">
            <div class="why-card">
                <div class="why-icon">
                    <i class='bx bx-target-lock'></i>
                </div>
                <h3>Personalized Plans</h3>
                <p>Every program is tailored to your specific goals, fitness level, and lifestyle preferences.</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class='bx bx-trending-up'></i>
                </div>
                <h3>Proven Results</h3>
                <p>Join thousands of members who have achieved their fitness goals with our evidence-based approach.</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class='bx bx-support'></i>
                </div>
                <h3>Expert Support</h3>
                <p>Get guidance from certified trainers and nutritionists available 24/7 through our app.</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class='bx bx-mobile-alt'></i>
                </div>
                <h3>Website Access</h3>
                <p>Access your workouts, track progress, and connect with trainers anywhere, anytime through our website.</p>
            </div>
        </div>
    </div>
</section>

<section class="testimonials-section">
    <div class="container">
        <h2>SUCCESS STORIES</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Program gym training di Training.IT benar-benar mengubah tubuh saya. Saya berhasil membangun massa otot dan kekuatan yang signifikan!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">W</div>
                        <div class="author-info">
                            <h4>William</h4>
                            <span>Gym Training</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Latihan calisthenics membantu saya meningkatkan kekuatan fungsional dan fleksibilitas tanpa perlu peralatan mahal."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">R</div>
                        <div class="author-info">
                            <h4>Raffikul</h4>
                            <span>Calisthenics Training</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Program cardio dan endurance membuat saya lebih sehat dan tahan lama. Saya bisa berlari lebih jauh sekarang!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">Z</div>
                        <div class="author-info">
                            <h4>Ziyad</h4>
                            <span>Cardio and Endurance Training</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>READY TO START YOUR TRANSFORMATION?</h2>
        <p>Join thousands of members who have already begun their fitness journey</p>
        <div class="cta-buttons">
            <button id="startProgramBtn" class="cta-primary-btn" onclick="window.location.href='{{ route('programs') }}'">START PROGRAM</button>
        </div>
    </div>
</section>

<x-site-footer />

<script src="{{ asset('assets/js/learn_more.js') }}"></script>
</body>
</html>
