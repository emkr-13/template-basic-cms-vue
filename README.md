<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Docker

Database selalu berada di luar Docker, baik untuk local maupun production. Container hanya menjalankan aplikasi Laravel dan web server.

### Development local

Development memakai file `.env` yang sudah ada. Database tetap harus berjalan di mesin local atau server database terpisah. Pastikan nilai berikut sesuai dengan MySQL yang aktif:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sample_template_cms_vue
DB_USERNAME=root
DB_PASSWORD=
```

Jika database belum ada, buat terlebih dahulu. Sesuaikan nama user dan database bila berbeda:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sample_template_cms_vue;"
```

Jalankan migrasi dari host jika Laravel juga dijalankan langsung dari host:

```bash
php artisan migrate
```

Jika Laravel dijalankan melalui Docker, jalankan container terlebih dahulu:

```bash
docker compose -f compose.dev.yaml up --build
```

Lalu, dari terminal lain, jalankan migrasi di dalam container:

```bash
docker compose -f compose.dev.yaml exec app php artisan migrate
```

Sebelum membuat akun Super Admin awal, isi credential ini pada `.env` (jangan commit password):

```env
INITIAL_SUPER_ADMIN_NAME="Super Admin"
INITIAL_SUPER_ADMIN_EMAIL="admin@example.test"
INITIAL_SUPER_ADMIN_PASSWORD="gunakan-password-yang-kuat"
```

Lalu seed ulang data akses:

```bash
docker compose -f compose.dev.yaml exec app php artisan db:seed
```

Login menggunakan email dan password tersebut. Permission dan role `super_admin` akan dibuat idempotent; menjalankan seeder kembali tidak akan menduplikasi data.

Compose development otomatis mengganti `DB_HOST` menjadi `host.docker.internal` hanya di dalam container, agar container dapat terhubung ke MySQL pada mesin local. Jangan mengubah `DB_HOST=127.0.0.1` di `.env` hanya untuk kebutuhan container.

Di terminal terpisah, jalankan Vite langsung dari mesin local (bukan dari Compose):

```bash
npm install
npm run dev
```

Buka aplikasi di `http://localhost:8000`. Vite akan tersedia di `http://localhost:5173` dan menyediakan hot reload untuk file Vue/CSS.

Untuk menghentikan development environment:

```bash
docker compose -f compose.dev.yaml down
```

### Production

Production membaca file `.env.prod`, bukan `.env`. File ini tidak masuk Git. Buat dari template lalu isi `APP_KEY`, domain production, dan kredensial database production:

```bash
cp .env.prod.example .env.prod
docker compose -f compose.prod.yaml up --build -d
```

Isi juga `INITIAL_SUPER_ADMIN_EMAIL` dan `INITIAL_SUPER_ADMIN_PASSWORD` pada `.env.prod` sebelum deployment pertama. Production menggunakan `MAIL_MAILER=smtp` beserta credential SMTP nyata agar invitation/reset-password dapat dikirim. Jangan gunakan password plaintext untuk invitation; sistem mengirim tautan reset password yang berlaku terbatas.

Application production tersedia di `http://localhost:8080` (atau pasang reverse proxy ke port tersebut). Asset frontend dibangun di dalam image, jadi tidak perlu menjalankan `npm run dev` di server production.

Container `app` production menjalankan `php artisan migrate --force --no-interaction` setiap mulai. Gunakan satu replica `app` saja selama memakai strategi migrasi otomatis ini.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
