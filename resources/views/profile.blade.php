<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Profile - Fitness Tracker</title>
    <link href="https://fonts.googleapis.com/css2=family=Montserrat:wght@100;300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* Styling internal sementara biar langsung rapi pas lu buka */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: #262626;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            margin: auto;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 0;
            color: #ff4757;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            background: #ff4757;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 800;
            color: white;
        }

        .profile-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .profile-header p {
            color: #aaa;
            font-size: 14px;
        }

        .section-title {
            font-size: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
            color: #ff4757;
            font-weight: 800;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #333;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card .value {
            font-size: 20px;
            font-weight: 800;
            color: #ff4757;
            margin-bottom: 5px;
        }

        .stat-card .label {
            font-size: 12px;
            color: #aaa;
        }

        .info-list {
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #333;
            font-size: 14px;
        }

        .info-item .label {
            color: #aaa;
        }

        .info-item .value {
            font-weight: 600;
        }

        .btn-action {
            display: block;
            width: 100%;
            background: #ff4757;
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-action:hover {
            background: #ff2e43;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="profile-header">
            <a href="/programs" class="back-btn">&larr; Programs</a>
            <div class="avatar" id="userAvatar">U</div>
            <h1 id="userName">User Fit</h1>
            <p id="userEmail">user@example.com</p>
        </div>

        <div class="section-title">Workout Progress (Cycles)</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="value" id="gymCycles">0</div>
                <div class="label">Gym</div>
            </div>
            <div class="stat-card">
                <div class="value" id="cardioCycles">0</div>
                <div class="label">Cardio</div>
            </div>
            <div class="stat-card">
                <div class="value" id="calisthenicsCycles">0</div>
                <div class="label">Calisthenics</div>
            </div>
        </div>

        <div class="section-title">Personal Info</div>
        <div class="info-list">
            <div class="info-item">
                <span class="label">Berat Badan</span>
                <span class="value">65 kg</span>
            </div>
            <div class="info-item">
                <span class="label">Tinggi Badan</span>
                <span class="value">170 cm</span>
            </div>
            <div class="info-item">
                <span class="label">Target</span>
                <span class="value">Membangun Otot & Stamina</span>
            </div>
        </div>

        <a href="#" class="btn-action">Edit Profile</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mengambil data jumlah workout cycle dari localStorage yang di-save dari halaman workout sebelumnya
            const gymCount = localStorage.getItem('workoutCycles:gym') || '0';
            const cardioCount = localStorage.getItem('workoutCycles:cardio') || '0';
            const calisthenicsCount = localStorage.getItem('workoutCycles:calisthenics') || '0';

            // Masukin nilainya ke HTML
            document.getElementById('gymCycles').textContent = gymCount;
            document.getElementById('cardioCycles').textContent = cardioCount;
            document.getElementById('calisthenicsCycles').textContent = calisthenicsCount;

            // Info Tambahan: Jika ke depannya data user ditarik dari Auth Laravel, 
            // kita tinggal ganti teks innerHTML pakai data dari backend.
        });
    </script>
</body>
</html>