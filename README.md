# Workout App (Laravel)

## Kebutuhan lokal

- Laragon dengan MySQL aktif (port `3306`)
- PHP `8.0.2` atau lebih baru, dengan ekstensi `pdo_mysql`
- Composer
- Node.js dan npm

## Menjalankan di Laragon

1. Jalankan MySQL dari Laragon.
2. Buat database kosong bernama `workout_app` di HeidiSQL/phpMyAdmin, atau jalankan:

   ```sql
   CREATE DATABASE workout_app
       CHARACTER SET utf8mb4
       COLLATE utf8mb4_unicode_ci;
   ```

3. Salin `.env.example` menjadi `.env` hanya jika belum memiliki `.env`. Jika `.env` sudah ada, pertahankan `APP_KEY` dan rahasia lain, lalu ubah pengaturan database lokalnya:

   ```dotenv
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=workout_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Jangan commit `.env` atau mengirim nilai `APP_KEY`/password database ke GitHub.

4. Dari folder project ini, jalankan:

   ```powershell
   composer install
   npm install
   npm run build
   php artisan key:generate
   php artisan optimize:clear
   php artisan migrate --seed
   php artisan serve
   ```

   Jika `.env` sudah memiliki `APP_KEY`, tidak perlu menjalankan `key:generate`; mengganti key dapat membuat sesi login lama tidak berlaku.

5. Buka `http://127.0.0.1:8000`, lalu daftar akun baru. Seeder hanya mengisi katalog program dan latihan, bukan akun pengguna.

Migration membuat tabel `programs`, `exercises`, `profiles`, `user_programs`, `workout_sessions`, dan `workout_progress`, beserta struktur pengguna bawaan Laravel. Seeder mengisi tiga program dan daftar latihan yang dipakai halaman workout.

## Menyiapkan database baru

Migration dan seed bersifat terpisah:

```powershell
php artisan migrate
php artisan db:seed
```

Untuk mengulang dari database lokal yang memang boleh dihapus, gunakan `php artisan migrate:fresh --seed`. Perintah itu menghapus seluruh tabel dan data pada database aktif; pastikan `.env` menunjuk ke `workout_app` lokal sebelum menjalankannya.

## Deploy ke Railway

Database MySQL Laragon hanya bisa diakses dari komputer lokal. Railway dapat
menjalankan aplikasi Laravel dan MySQL production sebagai service terpisah di
dalam project yang sama.

1. Buat repository GitHub untuk project ini. Untuk repository public, pastikan
   `.env` dan semua file `.env.*` selain `.env.example` tetap di-ignore.
2. Di Railway, buat project baru dan deploy service aplikasi dari repository
   GitHub tersebut. Railway mendeteksi Laravel dan menjalankannya dengan PHP-FPM
   dan Caddy.
3. Tambahkan service MySQL ke project Railway.
4. Set environment variables pada service aplikasi:

   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=<buat key unik untuk production>
   APP_URL=<domain Railway aplikasi>
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   ```

   Ganti `MySQL` pada referensi variabel dengan nama service database yang
   sebenarnya. Masukkan rahasia hanya di Railway Variables, jangan di GitHub.
5. Pastikan asset frontend dibangun (`npm run build`) saat proses build Railway.
   Setelah database production tersedia dan variabel koneksi sudah dicek, jalankan
   `php artisan migrate --force` sekali terhadap service aplikasi.
6. Generate domain publik pada pengaturan Networking service aplikasi, lalu
   perbarui `APP_URL` dengan domain tersebut dan deploy ulang.

Jangan menyalin database lokal atau kredensial Laragon ke production. Seeder
katalog dapat dijalankan terpisah jika database production baru membutuhkan
program dan daftar latihan: `php artisan db:seed --force`. Jangan menjalankan
`migrate:fresh` pada production.

Untuk menghindari menyimpan sesi login hanya di filesystem container, atur
session Laravel ke penyimpanan yang persisten sebelum membuka aplikasi untuk
pengguna. Konfigurasikan layanan email production sebelum mengandalkan fitur
reset password.
