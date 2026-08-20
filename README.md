<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="350" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12"></a>
<a href="https://vuejs.org"><img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="Vue 3"></a>
<a href="https://inertiajs.com"><img src="https://img.shields.io/badge/Inertia.js-v3-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia v3"></a>
<a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
<a href="https://docker.com"><img src="https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"></a>
</p>

---

# 🚀 Starter Template CMS Vue (Laravel 12 + Inertia v3)

Template starter-kit CMS profesional berbasis **Laravel 12**, **Inertia.js v3**, dan **Vue 3 (Composition API)**. Didesain siap pakai untuk proyek enterprise dengan manajemen akses berbasis role (RBAC), fitur ekspor-impor data, Docker development & production setup, serta terintegrasi penuh dengan **Laravel Boost & Agentic AI Coding Tools**.

---

## 📑 Daftar Isi
- [Fitur Utama](#-fitur-utama)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Development Setup (Docker)](#-development-setup-docker)
- [Penggunaan & Fitur AI Agent (Laravel Boost)](#-penggunaan--fitur-ai-agent-laravel-boost)
  - [Instalasi Boost di Docker](#1-instalasi--instalasi-ulang-boost-di-docker)
  - [Konfigurasi MCP Server (`mcp.json`)](#2-konfigurasi-mcp-server-mcpjson)
  - [Pembaruan Guidelines AI (`artisan boost:update`)](#3-pembaruan-guidelines-ai-artisan-boostupdate)
- [Testing Setup & Execution (.env.testing)](#-testing-setup--execution-envtesting)
  - [Persiapan File Environment Testing](#1-persiapan-file-environment-testing)
  - [Strategi Database Testing (Senior QA & Backend Standard)](#2-strategi-database-testing-senior-qa--backend-standard)
  - [Menjalankan Testing di Docker Container](#3-menjalankan-testing-di-docker-container)
- [Production Deployment](#-production-deployment)
- [Command Cheat Sheet](#-command-cheat-sheet)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

- **Architecture**: Laravel 12 (PHP 8.3) + Inertia.js v3 SPA tanpa kompleksitas API terpisah.
- **Frontend Stack**: Vue 3 (`<script setup>`), Vite, Tailwind CSS, Ziggy Route helper.
- **Role & Permission Management**: Integrasi Spatie `laravel-permission` (Roles, Permissions, & `super_admin` bypass).
- **Data Export & Import**: Siap pakai dengan `maatwebsite/excel` (Excel/CSV) & `barryvdh/laravel-dompdf` (PDF Export).
- **Isolated Docker Setup**: Containerization terpisah untuk Development (`compose.dev.yaml`) & Production (`compose.prod.yaml`). Database MySQL berjalan terpisah dari container.
- **Agentic AI Native Ready**: Terintegrasi langsung dengan **Laravel Boost (MCP Server)**, rules terstruktur (`.ai/rules`), dan Agent Skills (`.agents/skills/`) untuk akselerasi coding berbasis AI (Antigravity, Cursor, Claude Code, Copilot).

---

## ⚙️ Persyaratan Sistem

- **Docker** & **Docker Compose**
- **Node.js** `>= 18.x` & **NPM** (untuk frontend dev di host machine)
- **MySQL** `>= 8.0` (berjalan di host local atau database server terpisah)

---

## 🛠️ Development Setup (Docker)

Database berada di luar Docker container. Container Docker hanya menjalankan runtime PHP/Laravel.

### 1. Persiapan Database & File `.env`
Buat database MySQL di host machine:
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sample_template_cms_vue;"
```

Pastikan variabel database pada file `.env` terisi dengan benar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sample_template_cms_vue
DB_USERNAME=root
DB_PASSWORD=
```
> 💡 *Catatan:* `compose.dev.yaml` otomatis mengalihkan `DB_HOST` menjadi `host.docker.internal` secara internal di dalam container agar terhubung ke MySQL host. **Jangan ubah `DB_HOST=127.0.0.1` di file `.env`.**

### 2. Jalankan Docker Container Development
```bash
# 1. Jalankan container di background
docker compose -f compose.dev.yaml up -d --build

# 2. Jalankan migrasi database & Inisialisasi Role
docker compose -f compose.dev.yaml exec app php artisan migrate

# 3. Inisialisasi permissions, role, & buat akun Super Admin
docker compose -f compose.dev.yaml exec app php artisan role:init
```
*(Atau gunakan opsi `--name`, `--email`, `--password` untuk eksekusi non-interaktif)*

### 3. Jalankan Frontend Vite (Host Machine)
Buka terminal baru di mesin local dan jalankan Vite dev server:
```bash
npm install
npm run dev
```

Akses aplikasi di browser: **`http://localhost:8000`**  
*(Vite HMR akan aktif di `http://localhost:5173`)*

Untuk menghentikan container development:
```bash
docker compose -f compose.dev.yaml down
```

---

## 🤖 Penggunaan & Fitur AI Agent (Laravel Boost)

Proyek ini telah dikonfigurasi agar AI Coding Agents (seperti **Antigravity**, **Cursor**, **Claude Code**, **Copilot**) dapat bekerja dengan sangat presisi dan memahami konvensi aplikasi secara otomatis.

### 1. Instalasi & Instalasi Ulang Boost di Docker

Jika perlu melakukan setup/reset Laravel Boost di Docker:
```bash
# Install package composer Boost
docker compose -f compose.dev.yaml exec app composer require laravel/boost --dev

# Jalankan installer interaktif
docker compose -f compose.dev.yaml exec app php artisan boost:install
```

> **💡 Tips Navigasi Terminal Interaktif (Laravel Prompts):**
> - **`↑` / `↓`**: Pindah opsi pilihan.
> - **`Spacebar`**: Centang / hapus centang opsi (`◼` / `◻`).
> - **`a`**: Pilih semua opsi (*Select All*).
> - **`ENTER`**: Konfirmasi dan lanjut.

### 2. Konfigurasi MCP Server (`mcp.json`)

Agar AI Editor/Agent pada host machine dapat menggunakan tool MCP dari dalam container Docker, tambahkan konfigurasi berikut pada `mcp.json` editor Anda:

```json
{
  "mcpServers": {
    "laravel-boost": {
      "command": "docker",
      "args": [
        "compose",
        "-f",
        "compose.dev.yaml",
        "exec",
        "-i",
        "app",
        "php",
        "artisan",
        "boost:mcp"
      ]
    }
  }
}
```
*Flag `-i` wajib ada agar komunikasi `stdin`/`stdout` antara AI Agent di host dan container Docker berjalan lancar.*

### 3. Pembaruan Guidelines AI (`artisan boost:update`)

Ketika Anda menambahkan package baru, mengubah struktur folder, atau menambah aturan proyek:
```bash
# Refresh AI guidelines & skills di repositori
docker compose -f compose.dev.yaml exec app php artisan boost:update
```

---

## 🧪 Testing Setup & Execution (.env.testing)

Aplikasi ini menggunakan konfigurasi environment khusus pengujian melalui `.env.testing` dan `.env.testing.example` untuk memastikan isolasi penuh antara data pengujian dan database development lokal/production.

### 1. Persiapan File Environment Testing
Salin file `.env.testing.example` menjadi `.env.testing`:
```bash
cp .env.testing.example .env.testing
```
*Catatan:* File `.env.testing` telah secara otomatis dimasukkan ke `.gitignore` agar opsi testing lokal setiap pengembang tidak bentrok di repositori.

### 2. Strategi Database Testing (Senior QA & Backend Standard)

Terdapat dua pendekatan strategi database untuk pengujian:

1. **Opsi 1: Fast In-Memory SQLite (Default & Recommended for CI/Fast Feedback)**
   - Performa super cepat (eksekusi test di RAM tanpa I/O disk).
   - Tanpa perlu setup database tambahan di MySQL.
   - Konfigurasi di `.env.testing`:
     ```env
     DB_CONNECTION=sqlite
     DB_DATABASE=:memory:
     ```

2. **Opsi 2: Dedicated MySQL Test Database (Full MySQL Feature Parity)**
   - Digunakan jika terdapat feature test yang memerlukan spesifik fitur MySQL (misal: JSON column querying, stored procedure, atau raw MySQL query).
   - Buat database khusus testing di MySQL host:
     ```bash
     mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sample_template_cms_vue_test;"
     ```
   - Sesuaikan `.env.testing` untuk menggunakan MySQL:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=sample_template_cms_vue_test
     DB_USERNAME=root
     DB_PASSWORD=
     ```
     *(Di dalam Docker container `compose.dev.yaml`, `DB_HOST` otomatis dialihkan ke `host.docker.internal`)*

### 3. Best Practice Testing Configuration
- **Hashing Speedup (`BCRYPT_ROUNDS=4`)**: Mengurangi alokasi waktu hashing password saat membuat dummy user dengan factory.
- **State Isolation**: `CACHE_STORE=array`, `SESSION_DRIVER=array`, dan `QUEUE_CONNECTION=sync` untuk mencegah *leaking state* antar unit test.
- **Mail Trap (`MAIL_MAILER=array`)**: Mencegah pengiriman email asli saat pengujian berjalan.

### 4. Menjalankan Testing di Docker Container

Gunakan perintah Artisan `test` di dalam container Docker development:

```bash
# 1. Jalankan seluruh suite test (Unit & Feature)
docker compose -f compose.dev.yaml exec app php artisan test

# 2. Jalankan test pada file tertentu
docker compose -f compose.dev.yaml exec app php artisan test tests/Feature/Console/MakeSuperAdminCommandTest.php

# 3. Filter pengujian berdasarkan nama method / class
docker compose -f compose.dev.yaml exec app php artisan test --filter=MakeSuperAdmin

# 4. Jalankan test secara paralel untuk akselerasi eksekusi
docker compose -f compose.dev.yaml exec app php artisan test --parallel

# 5. Jalankan test dengan laporan code coverage (jika Xdebug/PCOV aktif)
docker compose -f compose.dev.yaml exec app php artisan test --coverage
```

---

## 🚢 Production Deployment

Pada server production, aplikasi menggunakan file `.env.prod` dan dikonfigurasi melalui `compose.prod.yaml`.

### 1. Konfigurasi File Environment
```bash
cp .env.prod.example .env.prod
```
Isi `APP_KEY`, domain production, serta kredensial database production.

### 2. Pengaturan Mailer SMTP
Pastikan `MAIL_MAILER=smtp` beserta kredensial SMTP diisi dengan benar agar sistem dapat mengirimkan email undangan user dan reset password.

### 3. Deploy Container Production & Super Admin
```bash
docker compose -f compose.prod.yaml up --build -d
docker compose -f compose.prod.yaml exec app php artisan make:super-admin
```
- Aplikasi production tersedia di **`http://localhost:8080`** (atau via reverse proxy Nginx/Traefik).
- Asset Vue/CSS sudah otomatis di-build di dalam image production (tidak memerlukan `npm run dev`).
- Container otomatis menjalankan `php artisan migrate --force --no-interaction` saat startup.

---

## 📋 Command Cheat Sheet

| Kebutuhan | Perintah Terminal |
|---|---|
| **Start Dev Container** | `docker compose -f compose.dev.yaml up -d` |
| **Stop Dev Container** | `docker compose -f compose.dev.yaml down` |
| **Exec Artisan** | `docker compose -f compose.dev.yaml exec app php artisan <command>` |
| **Run Migration** | `docker compose -f compose.dev.yaml exec app php artisan migrate` |
| **Init Roles & Super Admin** | `docker compose -f compose.dev.yaml exec app php artisan role:init` |
| **Run All Tests** | `docker compose -f compose.dev.yaml exec app php artisan test` |
| **Run Specific Test** | `docker compose -f compose.dev.yaml exec app php artisan test tests/Feature/Console/MakeSuperAdminCommandTest.php` |
| **Run Filtered Tests** | `docker compose -f compose.dev.yaml exec app php artisan test --filter=<Name>` |
| **Run Parallel Tests** | `docker compose -f compose.dev.yaml exec app php artisan test --parallel` |
| **Format Code (Pint)** | `docker compose -f compose.dev.yaml exec app vendor/bin/pint` |
| **Clear App Cache** | `docker compose -f compose.dev.yaml exec app php artisan optimize:clear` |
| **Update Boost Rules** | `docker compose -f compose.dev.yaml exec app php artisan boost:update` |

---

## 📄 Lisensi

Proyek ini menggunakan lisensi open-source [MIT License](LICENSE).
