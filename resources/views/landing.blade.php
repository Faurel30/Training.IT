<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fitness Landing Page</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/landing_page.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/site-footer.css') }}">

  <style>
    .footer-compact {
      background:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px),
        radial-gradient(circle at 15% 20%, rgba(255, 64, 74, 0.16), transparent 28%),
        radial-gradient(circle at 85% 80%, rgba(255, 64, 74, 0.10), transparent 30%),
        linear-gradient(135deg, #050505 0%, #0b0b0b 48%, #160708 100%);
      background-size: 52px 52px, 52px 52px, auto, auto, auto;
      color: #ffffff;
      border-top: 1px solid rgba(255, 64, 74, 0.35);
      font-family: 'Poppins', sans-serif;
      padding: 44px 7% 0;
    }

    .footer-container {
      max-width: 1250px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1.3fr 1.2fr 1.15fr 1fr;
      gap: 42px;
      align-items: flex-start;
    }

    .footer-brand-box {
      max-width: 320px;
    }

    .footer-logo-row {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 18px;
    }

    .footer-logo-icon {
      width: 54px;
      height: 54px;
      border-radius: 16px;
      background: rgba(255, 64, 74, 0.13);
      border: 1px solid rgba(255, 64, 74, 0.28);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      box-shadow: 0 10px 26px rgba(255, 64, 74, 0.13);
    }

    .footer-logo-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .footer-brand-title {
      margin: 0;
      font-size: 1.55rem;
      line-height: 1.1;
      font-weight: 900;
      letter-spacing: 0.5px;
      color: #ffffff;
    }

    .footer-brand-title span {
      color: #ff4a52;
    }

    .footer-brand-subtitle {
      margin-top: 3px;
      color: rgba(255, 255, 255, 0.62);
      font-size: 0.78rem;
      font-weight: 500;
    }

    .footer-description {
      color: rgba(255, 255, 255, 0.68);
      font-size: 0.88rem;
      line-height: 1.75;
      font-weight: 300;
      margin-bottom: 18px;
    }

    .footer-badge {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 999px;
      background: rgba(255, 64, 74, 0.10);
      border: 1px solid rgba(255, 64, 74, 0.26);
      color: #ff7a80;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.4px;
    }

    .footer-column h3 {
      font-size: 1rem;
      font-weight: 800;
      margin: 0 0 18px;
      color: #ffffff;
      position: relative;
      padding-bottom: 10px;
    }

    .footer-column h3::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 44px;
      height: 3px;
      border-radius: 999px;
      background: #ff4a52;
      box-shadow: 0 0 14px rgba(255, 64, 74, 0.45);
    }

    .footer-list {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .footer-list li {
      color: rgba(255, 255, 255, 0.68);
      font-size: 0.86rem;
      line-height: 1.7;
      margin-bottom: 9px;
      font-weight: 300;
    }

    .footer-list strong {
      color: #ff858a;
      font-weight: 700;
    }

    .footer-link {
      color: #ff858a;
      font-weight: 700;
      text-decoration: none;
    }

    .footer-link:hover {
      color: #ffffff;
      text-decoration: underline;
    }

    .footer-bottom {
      margin-top: 38px;
      padding: 18px 0;
      border-top: 1px solid rgba(255, 64, 74, 0.16);
      text-align: center;
      color: rgba(255, 255, 255, 0.58);
      font-size: 0.82rem;
      font-weight: 300;
    }

    .footer-bottom span {
      color: #ff6b70;
      font-weight: 700;
    }

    @media (max-width: 1100px) {
      .footer-container {
        grid-template-columns: 1fr 1fr;
        gap: 34px;
      }
    }

    @media (max-width: 700px) {
      .footer-compact {
        padding: 38px 24px 0;
      }

      .footer-container {
        grid-template-columns: 1fr;
        gap: 28px;
      }

      .footer-brand-box {
        max-width: 100%;
      }

      .footer-bottom {
        text-align: left;
        line-height: 1.7;
      }
    }
  </style>
</head>

<body>

<nav class="navbar">
  <div class="logo"> 
    <a href="{{ route('landing') }}" class="logo-link">
      <img class="logo-img" src="{{ asset('assets/img/logo_baru.png') }}" alt="TRAINING.IT LOGO">
    </a>
  </div>

  <ul class="menu">
    <li><a href="/" style="text-decoration: none; color: inherit;">HOME</a></li>
    <li><a href="/programs" style="text-decoration: none; color: inherit;">PROGRAMS</a></li>
    <li><a href="{{ route('about') }}" style="text-decoration: none; color: inherit;">ABOUT US</a></li>
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

<section class="hero" id="hero">

  <div class="hero-text">
    <h1>FITNESS</h1>
    <h2>THAT FITS YOUR LIFE</h2>

    <p>
      start your program now, and make your dream body come true.
      we help you to make your progress easier
    </p>

    <button class="cta-btn" id="startBtn" onclick="window.location.href='{{ auth()->check() ? route('programs') : route('login') }}'">
      START YOUR PROGRESS
    </button>
    
    <button class="secondary-btn" onclick="window.location.href='{{ route('about') }}'">
      LEARN MORE
    </button>
  </div>

  <div class="hero-bg"></div>

  <div class="hero-line line-1"></div>
  <div class="hero-line line-2"></div>
  <div class="hero-line line-3"></div>

  <div class="hero-rect"></div>
  <div class="hero-rect2"></div>
</section>

<footer class="footer-compact">
  <div class="footer-container">

    <div class="footer-brand-box">
      <div class="footer-logo-row">
        <div class="footer-logo-icon">
          <img src="{{ asset('assets/img/logo_baru.png') }}" alt="WorkoutApp Logo">
        </div>

        <div>
          <h2 class="footer-brand-title">Workout<span>App</span></h2>
          <div class="footer-brand-subtitle">Fitness Web Application</div>
        </div>
      </div>

      <p class="footer-description">
        Website fitness berbasis Laravel yang dikembangkan sebagai project Mata Kuliah Pemrograman Web.
      </p>

      <span class="footer-badge">Project Pemrograman Web</span>
    </div>

    <div class="footer-column">
      <h3>Nama Kelompok & NIM</h3>
      <ul class="footer-list">
        <li>Dwi Raffikul Rahman - 20240801075</li>
        <li>Muhammad Faurel Alfarizi - 20240801021</li>
        <li>Calvin Ananta Pratama - 20240801144</li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>Informasi Akademik</h3>
      <ul class="footer-list">
        <li><strong>Mata Kuliah:</strong> Pemrograman Web</li>
        <li><strong>Dosen Pengampu:</strong> Dewi Setiowati, A.Md., S.Pd., M.Tr.Kom.</li>
        <li><strong>Kelas:</strong> KH002</li>
        <li><strong>Tahun Akademik:</strong> 2025/2026 Genap</li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>Domain Website</h3>
      <ul class="footer-list">
        <li>
          <strong>Subdomain:</strong>
          <a href="https://workoutapp.xo.je" target="_blank" rel="noopener" class="footer-link">
            workoutapp.xo.je
          </a>
        </li>
        <li><strong>Hosting:</strong> InfinityFree</li>
        <li>Website dapat diakses online tanpa menjalankan server lokal.</li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    © 2026 <span>WorkoutApp</span>. Project Mata Kuliah <span>Pemrograman Web</span> - <span>KH002</span>.
  </div>
</footer>

<script src="{{ asset('assets/js/landing.js') }}"></script>

</body>
</html>