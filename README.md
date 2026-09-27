<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="350" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13"></a>
<a href="https://vuejs.org"><img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="Vue 3"></a>
<a href="https://inertiajs.com"><img src="https://img.shields.io/badge/Inertia.js-v3-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia v3"></a>
<a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
<a href="https://herd.laravel.com"><img src="https://img.shields.io/badge/Laravel_Herd-Supported-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Herd"></a>
<a href="https://docker.com"><img src="https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"></a>
</p>

---

# 🚀 Starter Template CMS Vue (Laravel 13 + Inertia v3)

Template starter-kit CMS profesional berbasis **Laravel 13**, **Inertia.js v3**, dan **Vue 3 (Composition API)**. Didesain siap pakai untuk proyek enterprise dengan manajemen akses berbasis role (RBAC), fitur ekspor-impor data, dukungan lingkungan development fleksibel via **Laravel Herd** maupun **Docker**, serta terintegrasi penuh dengan **Laravel Boost & Agentic AI Coding Tools**.

---

## 📑 Daftar Isi
- [Fitur Utama](#-fitur-utama)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Development Setup (Opsi 1: Laravel Herd - Rekomendasi)](#-development-setup-opsi-1-laravel-herd---rekomendasi)
  - [Troubleshooting Permission di Linux/macOS](#-troubleshooting-permission-linuxmacos)
- [Development Setup (Opsi 2: Docker Development)](#-development-setup-opsi-2-docker-development)
- [API Proof of Concept & Swagger Documentation](#-api-proof-of-concept--swagger-documentation)
- [Penggunaan & Fitur AI Agent (Laravel Boost)](#-penggunaan--fitur-ai-agent-laravel-boost)
  - [Setup Boost (Herd vs Docker)](#1-instalasi--setup-boost)
  - [Konfigurasi MCP Server (`mcp.json`)](#2-konfigurasi-mcp-server-mcpjson)
  - [Pembaruan Guidelines AI (`artisan boost:update`)](#3-pembaruan-guidelines-ai-artisan-boostupdate)
- [Testing Setup & Execution (.env.testing)](#-testing-setup--execution-envtesting)
  - [Persiapan File Environment Testing](#1-persiapan-file-environment-testing)
  - [Strategi Database Testing](#2-strategi-database-testing-senior-qa--backend-standard)
  - [Menjalankan Testing (Herd vs Docker)](#3-menjalankan-testing)
- [Production Deployment](#-production-deployment)
- [Command Cheat Sheet](#-command-cheat-sheet)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

- **Architecture**: Laravel 13 (PHP 8.3) + Inertia.js v3 SPA tanpa kompleksitas API terpisah.
- **Frontend Stack**: Vue 3 (`<script setup>`), Vite, dan Tailwind CSS v4.
- **Role & Permission Management**: Integrasi Spatie `laravel-permission` (Roles, Permissions, & `super_admin` bypass).
- **Flexible Local Environment**: Siap dijalankan langsung via **Laravel Herd** (`.test` domain, super cepat & tanpa overhead container) maupun **Docker** container (`compose.dev.yaml`).
- **API Proof of Concept**: Public/Private API versioned, Sanctum Bearer Token 1 jam, API credential Super Admin, dan Swagger/OpenAPI.
- **Data Export & Import**: Siap pakai dengan `maatwebsite/excel` (Excel/CSV) & `barryvdh/laravel-dompdf` (PDF Export).
- **Isolated Production Docker**: Siap deploy ke VPS dengan Docker production setup (`compose.prod.yaml`) dan Nginx reverse proxy SSL.
- **Agentic AI Native Ready**: Terintegrasi langsung dengan **Laravel Boost (MCP Server)**, rules terstruktur (`.ai/rules`), dan Agent Skills (`.agents/skills/`) untuk akselerasi coding berbasis AI (Antigravity, Cursor, Claude Code, Copilot).

---

## ⚙️ Persyaratan Sistem

Pilih salah satu lingkungan development yang Anda gunakan:

### Opsi A: Laravel Herd (Rekomendasi - Tercepat & Paling Ringan)
- **Laravel Herd** (macOS, Windows, atau Linux) dengan **PHP 8.3**
- **Composer** (bawaan Herd)
- **Node.js** `>= 18.x` & **NPM** (bawaan Herd / host)
- **MySQL** `>= 8.0` (Herd Pro Services atau MySQL server lokal)

### Opsi B: Docker Development
- **Docker** & **Docker Compose**
- **Node.js** `>= 18.x` & **NPM** (untuk frontend dev di host machine)
- **MySQL** `>= 8.0` (berjalan di host lokal atau database server terpisah)

---

## 🔌 API Proof of Concept & Swagger Documentation

### 📚 Swagger UI API Documentation
Dokumentasi interaktif Swagger UI dapat diakses di browser pada URL:
- **Laravel Herd**: 👉 **[https://template-basic-cms-vue.test/api/documentation](https://template-basic-cms-vue.test/api/documentation)**
- **Docker**: 👉 **[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)**

### 🌐 API Endpoints (v1)

Base URL:
- **Herd**: `https://template-basic-cms-vue.test`
- **Docker**: `http://localhost:8000`

| Method | Endpoint | Authentication | Keterangan |
|---|---|---|---|
| **GET** | `/api/v1/public/check` | Tidak perlu | Verifikasi Public API |
| **POST** | `/api/v1/auth/token` | `client_id` + `client_secret` | Menerbitkan Sanctum Bearer Token (Berlaku 1 jam) |
| **GET** | `/api/v1/private/check` | Header: `Bearer Token` | Verifikasi Private API |

### 🔄 Flow Autentikasi API Step-by-Step

1. **Buat Kredensial API**: Super Admin membuka menu **API Credentials** (`/api-credentials`) di CMS untuk membuat `client_id` dan `client_secret`.
2. **Terbitkan Token (`POST /api/v1/auth/token`)**:
   Kirim request dengan body JSON:
   ```json
   {
     "client_id": "YOUR_CLIENT_ID",
     "client_secret": "YOUR_CLIENT_SECRET"
   }
   ```
   Response akan mengembalikan `access_token` (Sanctum Bearer Token yang berlaku selama 1 jam).
3. **Gunakan Token pada Request Private**:
   Sertakan token pada HTTP Header `Authorization`:
   ```http
   Authorization: Bearer <access_token_anda>
   ```

Super Admin dapat mencabut (*revoke*) credential kapan saja melalui CMS, yang secara otomatis membatalkan seluruh Bearer Token aktif terkait.

### 🚀 Cara Inisialisasi & Regenerasi Swagger UI

**Jika Menggunakan Laravel Herd:**
```bash
# 1. Terapkan migration Sanctum dan API credential
php artisan migrate

# 2. Generate spesifikasi OpenAPI untuk Swagger UI
php artisan l5-swagger:generate

# 3. Jalankan test POC API
php artisan test --compact tests/Feature/ApiCredentialApiTest.php
```

**Jika Menggunakan Docker:**
```bash
# 1. Terapkan migration Sanctum dan API credential
docker compose -f compose.dev.yaml exec app php artisan migrate

# 2. Generate spesifikasi OpenAPI untuk Swagger UI
docker compose -f compose.dev.yaml exec app php artisan l5-swagger:generate

# 3. Jalankan test POC API
docker compose -f compose.dev.yaml exec app php artisan test --compact tests/Feature/ApiCredentialApiTest.php
```

> 💡 **Catatan Regenerasi Swagger:** Setiap kali Anda menambahkan API endpoint baru atau memperbarui annotasi OpenAPI/Swagger pada controller, jalankan kembali perintah `php artisan l5-swagger:generate` agar file dokumentasi Swagger UI (`storage/api-docs/api-docs.json`) diperbarui secara otomatis.

---

## ⚡ Development Setup (Opsi 1: Laravel Herd - Rekomendasi)

Laravel Herd adalah opsi tercepat dan paling efisien untuk development lokal karena menjalankan PHP 8.3 & Nginx secara native tanpa overhead container.

### 1. Link / Park Project di Laravel Herd
Buka folder project di terminal, lalu link ke Herd:
```bash
cd /mnt/template/template-basic-cms-vue
herd link template-basic-cms-vue
```
*Domain lokal Anda akan otomatis aktif di: **`https://template-basic-cms-vue.test`***

### 2. Persiapan Database & File `.env`
Buat database MySQL lokal:
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sample_template_cms_vue;"
```

Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

Pastikan konfigurasi `.env` sesuai dengan environment lokal Anda:
```env
APP_NAME="Starter Template CMS Vue"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=https://template-basic-cms-vue.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sample_template_cms_vue
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
```

### 4. Setup Application Key, Database, & Roles
```bash
# 1. Generate Application Key
php artisan key:generate

# 2. Jalankan migrasi database
php artisan migrate

# 3. Buat symlink storage upload publik
php artisan storage:link

# 4. Inisialisasi permissions, role, & akun Super Admin awal
php artisan role:init
```

### 5. Jalankan Frontend Vite
Buka terminal dan jalankan Vite dev server:
```bash
npm run dev
```

Akses aplikasi di browser: **`https://template-basic-cms-vue.test`** 🎉

---

### 🛡️ Troubleshooting Permission (Linux/macOS)

Jika Anda melihat error seperti:
- `file_put_contents(...): Failed to open stream: Permission denied`
- `Error: EACCES: permission denied, mkdir '...'`
- `Unable to write file ...`

Hal ini biasanya terjadi karena ada file/folder (misalnya di `storage/` atau `.agents/`) yang secara tidak sengaja terbuat oleh user `root` (karena pernah menjalankan perintah dengan `sudo`).

**Solusi Perbaikan (Jalankan sekali di Terminal):**
```bash
# 1. Ubah seluruh kepemilikan file & folder project ke user Anda saat ini
sudo chown -R $USER:$USER .

# 2. Berikan izin baca/tulis untuk folder storage dan bootstrap cache
chmod -R 775 storage bootstrap/cache
```
Setelah perintah ini dijalankan, Herd dan IDE Anda dapat membaca/menulis file tanpa kendala izin.

---

## 🐳 Development Setup (Opsi 2: Docker Development)

Gunakan Docker jika Anda ingin runtime PHP/Laravel terisolasi di dalam container tanpa menginstal PHP langsung di host machine.

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

# 3. Buat symlink upload publik untuk avatar/file public
docker compose -f compose.dev.yaml exec app php artisan storage:link

# 4. Inisialisasi permissions, role, & buat akun Super Admin
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

### 1. Instalasi & Setup Boost

**Jika Menggunakan Laravel Herd:**
```bash
# Install package composer Boost
composer require laravel/boost --dev

# Jalankan installer interaktif
php artisan boost:install
```

**Jika Menggunakan Docker:**
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

Tambahkan konfigurasi berikut pada file `mcp.json` editor/AI Agent Anda:

**Opsi A: Laravel Herd (Host Native)**
```json
{
  "mcpServers": {
    "laravel-boost": {
      "command": "php",
      "args": [
        "artisan",
        "boost:mcp"
      ]
    }
  }
}
```

**Opsi B: Docker Development**
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
*Pada Docker, flag `-i` wajib ada agar komunikasi `stdin`/`stdout` antara AI Agent di host dan container Docker berjalan lancar.*

### 3. Pembaruan Guidelines AI (`artisan boost:update`)

Ketika Anda menambahkan package baru, mengubah struktur folder, atau menambah aturan proyek:
```bash
# Laravel Herd
php artisan boost:update

# Docker
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

### 4. Menjalankan Testing

**Jika Menggunakan Laravel Herd (Host Native):**
```bash
# 1. Jalankan seluruh suite test (Unit & Feature)
php artisan test

# 2. Jalankan test pada file tertentu
php artisan test tests/Feature/UserControllerTest.php

# 3. Filter pengujian berdasarkan nama method / class
php artisan test --filter=MakeSuperAdmin

# 4. Jalankan test secara paralel untuk akselerasi eksekusi
php artisan test --parallel

# 5. Jalankan test dengan laporan code coverage (jika Xdebug/PCOV aktif)
php artisan test --coverage
```

**Jika Menggunakan Docker Container:**
```bash
# 1. Jalankan seluruh suite test (Unit & Feature)
docker compose -f compose.dev.yaml exec app php artisan test

# 2. Jalankan test pada file tertentu
docker compose -f compose.dev.yaml exec app php artisan test tests/Feature/UserControllerTest.php

# 3. Filter pengujian berdasarkan nama method / class
docker compose -f compose.dev.yaml exec app php artisan test --filter=MakeSuperAdmin

# 4. Jalankan test secara paralel untuk akselerasi eksekusi
docker compose -f compose.dev.yaml exec app php artisan test --parallel

# 5. Jalankan test dengan laporan code coverage (jika Xdebug/PCOV aktif)
docker compose -f compose.dev.yaml exec app php artisan test --coverage
```

---

## 🚢 Production Deployment (Panduan Lengkap VPS Kosong hingga HTTPS)

Pada server production (seperti DigitalOcean, AWS, Linode, VPS Ubuntu 22.04/24.04), aplikasi dikonfigurasi menggunakan `compose.prod.yaml` dan file `.env.prod`.

---

### 1. Persiapan VPS Baru (Fresh VPS Setup)

Buka koneksi SSH ke VPS baru Anda dan jalankan instalasi **Docker**, **Nginx Host**, dan **Certbot**:

```bash
# 1. Update package system & install dependency awal
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git nginx certbot python3-certbot-nginx

# 2. Install Docker Engine resmi
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker
```

---

### 2. Clone Repositori & Konfigurasi File Environment

```bash
# 1. Clone repositori ke VPS
git clone <URL_REPOSITORI_ANDA> /var/www/cms-app
cd /var/www/cms-app

# 2. Salin file environment production
cp .env.prod.example .env.prod
```

Edit file `.env.prod` (`nano .env.prod`) dan sesuaikan konfigurasi berikut:
- `APP_KEY=`: (Akan digenerate di langkah berikutnya).
- `APP_URL=https://cms.domainanda.com`
- `DB_HOST=`: Alamat server database MySQL Anda (misal `127.0.0.1` atau Host IP).
- `DB_DATABASE=`, `DB_USERNAME=`, `DB_PASSWORD=`
- `MAIL_MAILER=smtp` beserta kredensial SMTP email.

---

### 3. Inisialisasi Container Production & Key Generation

```bash
# 1. Jalankan container pertama kali
docker compose -f compose.prod.yaml up --build -d

# 2. Generate APP_KEY di environment production
docker compose -f compose.prod.yaml exec app php artisan key:generate --force

# 3. Inisialisasi Role & buat akun Super Admin pertama
docker compose -f compose.prod.yaml exec app php artisan role:init
docker compose -f compose.prod.yaml exec app php artisan make:super-admin
```

> **💡 Catatan Service Deploy:** Service `deploy` pada `compose.prod.yaml` secara otomatis memproses `php artisan migrate --force` dan caching (`config:cache`, `route:cache`, `view:cache`) setiap kali container production di-up/rebuild.

---

### 4. Konfigurasi Nginx Host & SSL HTTPS (Certbot)

Aplikasi production berjalan di port `8080` di dalam Docker. Konfigurasikan Nginx di host machine sebagai **Reverse Proxy** dan pasang SSL HTTPS gratis via Certbot.

#### A. Buat Konfigurasi Block Nginx:
```bash
sudo nano /etc/nginx/sites-available/cms-app
```

Isikan dengan konfigurasi berikut (ganti `cms.domainanda.com` dengan domain asli Anda):

```nginx
server {
    listen 80;
    server_name cms.domainanda.com;

    client_max_body_size 20M;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

#### B. Aktifkan Site & Sertifikat SSL Certbot:
```bash
# 1. Symlink konfigurasi Nginx
sudo ln -s /etc/nginx/sites-available/cms-app /etc/nginx/sites-enabled/

# 2. Uji sintaks Nginx & reload
sudo nginx -t
sudo systemctl reload nginx

# 3. Dapatkan Sertifikat SSL HTTPS Otomatis dari Let's Encrypt
sudo certbot --nginx -d cms.domainanda.com
```
*Certbot akan otomatis memperbarui konfigurasi Nginx menjadi HTTPS (Port 443) dan mengatur Auto-Renewal SSL.*

Aplikasi CMS Production Anda kini resmi aktif & aman di **`https://cms.domainanda.com`**! 🎉

---

### 🔄 5. Alur Pembaruan Aplikasi di Production (Deploying Updates)

Setiap kali Anda merilis fitur baru atau melakukan `git push` dari komputer lokal, ikuti alur **DevOps Best Practice** berikut di server VPS:

```bash
# 1. Tarik pembaruan kode terbaru dari git
git pull origin main

# 2. Build image production terlebih dahulu (Isolasi Keamanan Build)
docker compose -f compose.prod.yaml build

# 3. Restrukturisasi & jalankan container baru serta bersihkan container yatim (clean up)
docker compose -f compose.prod.yaml up -d --remove-orphans
```

> **💡 Keunggulan Metode Ini:**
> - **Build Terpisah (`build`)**: Jika ada *syntax error* atau kegagalan saat build image, container production lama **tetap berjalan aktif** tanpa mengalami *downtime*.
> - **Clean Up (`--remove-orphans`)**: Menghapus container/service lama yang mungkin sudah tidak terikat di `compose.prod.yaml` sehingga resource VPS tidak terbuang sia-sia.
> - **Otomatisasi Migration & Cache**: Service `deploy` akan tetap otomatis mengeksekusi migrasi database baru dan me-refresh cache (`route`, `config`, `view`) tanpa perlu langkah manual.

---

## 📋 Command Cheat Sheet

| Kebutuhan | Laravel Herd (Host Native) | Docker Development |
|---|---|---|
| **Menjalankan App** | `herd link` + `npm run dev` | `docker compose -f compose.dev.yaml up -d` + `npm run dev` |
| **Menghentikan App** | Otomatis background | `docker compose -f compose.dev.yaml down` |
| **Eksekusi Artisan** | `php artisan <command>` | `docker compose -f compose.dev.yaml exec app php artisan <command>` |
| **Migrasi Database** | `php artisan migrate` | `docker compose -f compose.dev.yaml exec app php artisan migrate` |
| **Init Roles & Admin** | `php artisan role:init` | `docker compose -f compose.dev.yaml exec app php artisan role:init` |
| **Jalankan Semua Test** | `php artisan test` | `docker compose -f compose.dev.yaml exec app php artisan test` |
| **Test File Tertentu** | `php artisan test tests/Feature/UserControllerTest.php` | `docker compose -f compose.dev.yaml exec app php artisan test tests/Feature/UserControllerTest.php` |
| **Filter Test Method** | `php artisan test --filter=<Name>` | `docker compose -f compose.dev.yaml exec app php artisan test --filter=<Name>` |
| **Test POC API Sanctum** | `php artisan test --compact tests/Feature/ApiCredentialApiTest.php` | `docker compose -f compose.dev.yaml exec app php artisan test --compact tests/Feature/ApiCredentialApiTest.php` |
| **Generate Swagger UI** | `php artisan l5-swagger:generate` | `docker compose -f compose.dev.yaml exec app php artisan l5-swagger:generate` |
| **Jalankan Parallel Test**| `php artisan test --parallel` | `docker compose -f compose.dev.yaml exec app php artisan test --parallel` |
| **Format Code (Pint)** | `vendor/bin/pint` | `docker compose -f compose.dev.yaml exec app vendor/bin/pint` |
| **Clear App Cache** | `php artisan optimize:clear` | `docker compose -f compose.dev.yaml exec app php artisan optimize:clear` |
| **Update Boost Rules** | `php artisan boost:update` | `docker compose -f compose.dev.yaml exec app php artisan boost:update` |
| **Fix Permission (Linux)**| `sudo chown -R $USER:$USER . && chmod -R 775 storage bootstrap/cache` | N/A (Docker runtime) |
| **Deploy Production VPS** | N/A (Docker VPS) | `docker compose -f compose.prod.yaml build && docker compose -f compose.prod.yaml up -d --remove-orphans` |

---

## 📐 Arsitektur Database & ERD

```mermaid
erDiagram
    USERS ||--o{ ACTIVITY_LOGS : "mencatat"
    USERS ||--o{ API_CREDENTIALS : "memiliki"
    USERS }|--|{ ROLES : "model_has_roles"
    ROLES }|--|{ PERMISSIONS : "role_has_permissions"

    USERS {
        bigint id PK
        string name
        string email
        string avatar
        string status
        boolean must_change_password
        timestamp invited_at
        timestamp deleted_at
    }

    ACTIVITY_LOGS {
        bigint id PK
        bigint user_id FK
        string action
        string description
        string ip_address
        timestamp created_at
    }

    API_CREDENTIALS {
        bigint id PK
        bigint user_id FK
        string client_id
        string name
        timestamp revoked_at
    }
```

---

## 🛠️ Panduan Menambah Modul Baru (Developer Guide)

Gunakan alur 5 langkah standar berikut untuk menambahkan modul/fitur baru di CMS ini secara konsisten:

1. **Buat Migration & Model**:
   - **Herd**: `php artisan make:model Product -m`
   - **Docker**: `docker compose -f compose.dev.yaml exec app php artisan make:model Product -m`
2. **Buat Form Request & Controller**:
   - **Herd**:
     ```bash
     php artisan make:request StoreProductRequest
     php artisan make:controller ProductController
     ```
   - **Docker**:
     ```bash
     docker compose -f compose.dev.yaml exec app php artisan make:request StoreProductRequest
     docker compose -f compose.dev.yaml exec app php artisan make:controller ProductController
     ```
3. **Daftarkan Route & Permission**:
   Tambahkan middleware `permission:product.view` pada `routes/web.php` dan daftarkan permission baru di `app/Enums/PermissionEnum.php` agar `role:init` membuatnya secara idempoten.
4. **Buat Halaman Vue di `resources/js/Pages/Products/`**:
   Bungkus halaman dengan `<AuthenticatedLayout title="Products">` dan gunakan komponen reusable (`Card`, `TextInput`, `SearchFilterBar`, `StatusBadge`).
5. **Buat Feature Test & Jalankan Format Code**:
   - **Herd**:
     ```bash
     php artisan make:test ProductControllerTest
     php artisan test --filter=ProductControllerTest
     vendor/bin/pint
     ```
   - **Docker**:
     ```bash
     docker compose -f compose.dev.yaml exec app php artisan make:test ProductControllerTest
     docker compose -f compose.dev.yaml exec app php artisan test --filter=ProductControllerTest
     docker compose -f compose.dev.yaml exec app vendor/bin/pint
     ```

---

## 📄 Lisensi

Tambahkan lisensi proyek yang dipilih sebelum template didistribusikan ke publik.
