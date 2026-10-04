# Training.IT

Training.IT adalah aplikasi web workout yang membantu pengguna memilih program
latihan, mengikuti sesi workout, dan mencatat progres latihan. Project ini
dibuat menggunakan Laravel sebagai backend, MySQL sebagai database, serta Blade
dan Vite untuk halaman dan asset frontend.

## Fitur

- Registrasi, login, logout, dan alur reset password.
- Onboarding setelah registrasi: pilih gender, lalu pilih program latihan.
- Halaman latihan Gym, Cardio, dan Calisthenics.
- Pencatatan sesi workout dan progres latihan.
- Profil pengguna yang dapat dilihat dan diperbarui.
- Katalog program dan latihan yang dapat dibuat ulang menggunakan database
  migration dan seeder.

## Teknologi

- PHP 8.0.2 atau lebih baru
- Laravel 9
- MySQL
- Blade
- Vite, Node.js, dan npm

## Menjalankan secara lokal dengan Laragon

### Prasyarat

- Laragon dengan MySQL aktif.
- PHP 8.0.2+ dengan ekstensi `pdo_mysql`.
- Composer.
- Node.js dan npm.

### Setup

1. Clone repository dan masuk ke folder project:

   ```powershell
   git clone https://github.com/Faurel30/Training.IT.git
   cd Training.IT
   ```

2. Jalankan MySQL dari Laragon. Buat database kosong bernama `workout_app`
   melalui HeidiSQL/phpMyAdmin, atau jalankan SQL berikut:

   ```sql
   CREATE DATABASE workout_app
       CHARACTER SET utf8mb4
       COLLATE utf8mb4_unicode_ci;
   ```

3. Pasang dependency PHP dan frontend:

   ```powershell
   composer install
   npm install
   ```

4. Buat file konfigurasi lokal dengan menyalin `.env.example`:

   ```powershell
   Copy-Item .env.example .env
   ```

   Atur koneksi database lokal di `.env`:

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

   Buat application key dan siapkan database:

   ```powershell
   php artisan key:generate
   php artisan migrate --seed
   ```

5. Build asset frontend dan jalankan server:

   ```powershell
   npm run build
   php artisan serve
   ```

6. Buka <http://127.0.0.1:8000> dan buat akun untuk mencoba alur onboarding.

Seeder mengisi katalog program dan latihan; akun pengguna dibuat melalui
halaman registrasi aplikasi.

## Database

Migration Laravel mendefinisikan struktur database, sedangkan seeder mengisi
katalog latihan awal:

```powershell
php artisan migrate
php artisan db:seed
```

Untuk mengulang database lokal dari awal, `php artisan migrate:fresh --seed`
akan menghapus semua tabel dan data pada database yang sedang dikonfigurasi.
Gunakan hanya jika database tersebut boleh dihapus.

## Catatan keamanan

- Jangan commit `.env`, password database, atau `APP_KEY` ke repository.
- `.env.example` disediakan sebagai template; buat `.env` lokal sendiri.
- Jangan menaruh data atau kredensial Laragon pada README maupun GitHub.

## Lisensi

Project ini dibuat sebagai project portofolio pembelajaran.
