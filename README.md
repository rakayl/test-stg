# 📦 Project Name

> Aplikasi web fullstack menggunakan **Laravel** sebagai REST API backend dan **Vue.js** sebagai SPA frontend (arsitektur dipisah).

---

## 🏗️ Arsitektur

Proyek ini menggunakan pendekatan **Dipisah (Decoupled Architecture)**:

- **Backend** → Laravel berjalan sebagai REST API murni, mengembalikan response dalam format JSON.
- **Frontend** → Vue.js berjalan sebagai Single Page Application (SPA) yang terpisah, mengonsumsi API dari backend.

### Alasan Memilih Arsitektur Dipisah

1. **Separation of Concerns** — Backend dan frontend dapat dikembangkan, di-deploy, dan di-scale secara independen.
2. **Fleksibilitas Frontend** — Vue SPA memberikan pengalaman pengguna yang lebih responsif dan dinamis tanpa full-page reload.
3. **Reusabilitas API** — REST API yang sama dapat digunakan oleh aplikasi mobile atau klien lain di masa depan.
4. **Kemudahan Tim** — Developer backend dan frontend dapat bekerja paralel tanpa saling bergantung pada satu codebase.
5. **Deployment Bebas** — Backend bisa di-host di server terpisah (misalnya VPS/cloud), sementara frontend bisa di-deploy ke layanan static hosting seperti Vercel atau Netlify.

---

## 🛠️ Tech Stack

| Layer       | Teknologi                   |
|-------------|-----------------------------|
| Backend     | Laravel 13                  |
| Frontend    | Vue 3 + Vite                |
| Auth        | Laravel Sanctum (SPA Token) |
| Database    | POSTGRESQL                  |
| HTTP Client | Axios                       |

---

## ⚙️ Instalasi & Menjalankan Project

### Prasyarat

Pastikan sudah terinstall:
- PHP >= 8.3
- Composer
- Node.js >= 18 & NPM
- MySQL
- Git

---

### 1. Clone Repository

```bash
git clone https://github.com/rakayl/test-stg.git
cd test-stg
```

---

### 2. Setup Backend (Laravel)

```bash
# Masuk ke folder backend
cd siswa_backend

# Install dependency PHP
composer install

# Salin file environment
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

```bash
# Jalankan migrasi dan seeder
php artisan migrate --seed

# Jalankan server Laravel
php artisan serve
```

Backend akan berjalan di: `http://localhost:8000`

---

### 3. Setup Frontend (Vue.js)

```bash
# Masuk ke folder frontend (dari root project)
cd ../siswa-frontend

# Install dependency Node
npm install

# Salin file environment
cp .env.example .env
```

Edit file `.env` dan sesuaikan URL API:

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

```bash
# Jalankan development server
npm run dev
```

Frontend akan berjalan di: `http://localhost:5173`

---

## 👤 Contoh Akun untuk Login

| Email                 | Password   |
|-----------------------|------------|
| admin@example.com     | password   |

> Akun ini dibuat otomatis saat menjalankan `php artisan migrate --seed`.

---

## 📁 Struktur Folder

```
test-stg/
├── .gitignore
├── README.md
│
├── siswa_backend/                         # Laravel REST API
│   ├── app/
│   │   ├── Exports/                       # Export Excel (Siswa, Nilai)
│   │   ├── Imports/                       # Import Excel
│   │   │   ├── SiswaImport.php
│   │   │   └── NilaiImport.php
│   │   │
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/
│   │   │   │   │   ├── AuthController.php       # login & register
│   │   │   │   │   ├── DashboardController.php  # summary data
│   │   │   │   │   ├── SiswaController.php      # CRUD siswa
│   │   │   │   │   └── NilaiController.php      # CRUD nilai
│   │   │   │   └── Controller.php
│   │   │   │
│   │   │   ├── Requests/                        # Validasi request
│   │   │   │   ├── LoginRequest.php
│   │   │   │   ├── RegisterRequest.php
│   │   │   │   ├── StoreSiswaRequest.php
│   │   │   │   ├── UpdateSiswaRequest.php
│   │   │   │   ├── StoreNilaiRequest.php
│   │   │   │   └── UpdateNilaiRequest.php
│   │   │   │
│   │   │   └── Resources/                       # Format response API
│   │   │       ├── SiswaResource.php
│   │   │       └── NilaiResource.php
│   │   │
│   │   ├── Models/
│   │   │   ├── User.php                         # Auth user
│   │   │   ├── Siswa.php                        # Model siswa
│   │   │   └── Nilai.php                        # Model nilai
│   │   │
│   │   ├── Repositories/                        # Data access layer
│   │   │   ├── Contracts/
│   │   │   │   ├── AuthRepositoryInterface.php
│   │   │   │   ├── DashboardRepositoryInterface.php
│   │   │   │   ├── SiswaRepositoryInterface.php
│   │   │   │   └── NilaiRepositoryInterface.php
│   │   │   │
│   │   │   ├── AuthRepository.php
│   │   │   ├── DashboardRepository.php
│   │   │   ├── SiswaRepository.php
│   │   │   └── NilaiRepository.php
│   │   │
│   │   ├── Services/                            # Business logic
│   │   │   ├── AuthService.php
│   │   │   ├── DashboardService.php
│   │   │   ├── SiswaService.php
│   │   │   ├── NilaiService.php
│   │   │   └── LogicalService.php
│   │   │
│   │   └── Providers/
│   │       └── AppServiceProvider.php
│   │
│   ├── routes/
│   │   └── api.php                              # Endpoint API
│   │
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │
│   ├── config/
│   │   ├── cors.php                             # CORS Vue
│   │   └── sanctum.php                          # Auth token
│   │
│   ├── .env
│   ├── composer.json
│   └── artisan
│
└── siswa-frontend/                              # Vue 3 SPA
    ├── src/
    │   ├── api/                                 # Axios config
    │   │   └── axios.js
    │   │
    │   ├── assets/                              # Static assets
    │   │
    │   ├── components/                          # Reusable UI
    │   │   └── ...
    │   │
    │   ├── layouts/                             # Layout (Dashboard, Auth)
    │   │   └── ...
    │   │
    │   ├── pages/                               # Halaman utama
    │   │   ├── Login.vue
    │   │   ├── Dashboard.vue
    │   │   ├── Siswa.vue
    │   │   └── Nilai.vue
    │   │
    │   ├── router/
    │   │   └── index.js                         # Routing + guard
    │   │
    │   ├── stores/                              # Pinia store
    │   │   └── auth.js
    │   │
    │   ├── App.vue                              # Root component
    │   ├── main.js                              # Entry point
    │   └── style.css
    │
    ├── .env
    ├── package.json
    ├── vite.config.js
    ├── tailwind.config.js
    └── index.html
```

---

## 🔗 API Endpoints (contoh)

| Method | Endpoint           | Keterangan          | Auth |
|--------|--------------------|---------------------|------|
| POST   | /api/login         | Login user          | ❌   |
| POST   | /api/logout        | Logout user         | ✅   |
| GET    | /api/user          | Data user login     | ✅   |
| GET    | /api/items         | Daftar semua item   | ✅   |
| POST   | /api/items         | Tambah item baru    | ✅   |
| PUT    | /api/items/{id}    | Update item         | ✅   |
| DELETE | /api/items/{id}    | Hapus item          | ✅   |

> Semua endpoint yang membutuhkan auth menggunakan **Bearer Token** dari Laravel Sanctum.

---

