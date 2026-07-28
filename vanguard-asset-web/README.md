# VanguardAsset 🛡️

### Enterprise Asset Management & RBAC System

**Proyek UAS — Object-Oriented Programming**

Sistem manajemen aset perusahaan berbasis web dengan fitur RBAC (Role-Based Access Control), perhitungan depresiasi OOP, maintenance scheduler, dan peminjaman aset dengan tracking overdue.

---

## Prasyarat (Yang Harus Di-install)

Sebelum menjalankan, pastikan kamu sudah menginstall:

| Software | Versi | Link Download |
|---|---|---|
| **PHP** | 8.2+ | https://windows.php.net/download |
| **Composer** | Terbaru | https://getcomposer.org/download |
| **Python** | 3.10+ | https://www.python.org/downloads |
| **MySQL** (XAMPP/Laragon) | Terbaru | https://www.apachefriends.org atau https://laragon.org |
| **Git** *(opsional)* | Terbaru | https://git-scm.com/downloads |

> **Cara cek apakah sudah terinstall:**
> Buka terminal/PowerShell, lalu ketik:
> ```
> php --version
> composer --version
> python --version
> mysql --version
> ```
> Jika muncul versi masing-masing, berarti sudah terinstall.

---

## Cara Menjalankan (Step-by-Step)

### Langkah 1: Buat Database MySQL

1. Buka **phpMyAdmin** (http://localhost/phpmyadmin) atau MySQL CLI
2. Buat database baru dengan nama:
   ```
   vanguard_asset
   ```
3. Tidak perlu buat tabel — migration Laravel akan membuatnya otomatis

---

### Langkah 2: Jalankan Python FastAPI (Terminal 1)

Buka terminal **pertama**:

```bash
# Masuk ke folder API
cd vanguard-asset-api

# Buat virtual environment (opsional tapi disarankan)
python -m venv venv
venv\Scripts\activate

# Install dependencies Python
pip install -r requirements.txt

# Jalankan server FastAPI
python -m uvicorn main:app --host 127.0.0.1 --port 8001 --reload
```

**Tanda berhasil:** Lihat output `Uvicorn running on http://127.0.0.1:8001`

> Test API: Buka browser ke http://127.0.0.1:8001/docs — akan muncul halaman Swagger API Docs

---

### Langkah 3: Jalankan Laravel (Terminal 2)

Buka terminal **kedua**:

```bash
# Masuk ke folder web
cd vanguard-asset-web

# Install dependencies Laravel
composer install

# Copy file environment
copy .env.example .env

# Generate application key
php artisan key:generate
```

**Edit file `.env`** — sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vanguard_asset
DB_USERNAME=root
DB_PASSWORD=
```

> Jika kamu pakai password MySQL, isi `DB_PASSWORD` dengan password kamu.

```bash
# Jalankan migration + seeder (buat tabel & data awal)
php artisan migrate --seed

# Jalankan server Laravel
php artisan serve --port=8000
```

**Tanda berhasil:** Lihat output `Server running on http://127.0.0.1:8000`

---

### Langkah 4: Buka Aplikasi

Buka browser ke **http://localhost:8000** dan login dengan akun default.

---

## Akun Default

| Role | Email | Password |
|---|---|---|
| **IT Admin** | admin@vanguard.com | password |
| **Manager** | manager@vanguard.com | password |
| **Staff** | staff@vanguard.com | password |

> **IT Admin** — Akses penuh: CRUD aset, approve peminjaman, user management, security center
> **Manager** — Akses: Lihat aset, approve peminjaman, hitung depresiasi
> **Staff** — Akses: Lihat aset, ajukan peminjaman, kembalikan aset

---

## Fitur Utama

| Fitur | Deskripsi |
|---|---|
| **Manajemen Aset** | CRUD aset fisik (laptop, kamera) dan digital (lisensi software) |
| **RBAC (Role-Based Access Control)** | 3 role dengan hak akses berbeda |
| **OOP Depreciation Engine** | Perhitungan depresiasi dengan 3 metode (Garis Lurus, Saldo Menurun, Sum of Years) via FastAPI |
| **Peminjaman Aset** | Request → Approve/Reject → Return, dengan due date & overdue tracking |
| **Maintenance Scheduler** | Jadwal maintenance otomatis berdasarkan interval |
| **Security Center** | Log aktivitas, session management, security headers |
| **Dashboard Analytics** | Statistik aset, progress ring, sparkline, top aset termahal |
| **Export Data** | Export CSV dan PDF |

---

## Struktur Proyek

```
vanguardasset/
├── vanguard-asset-api/        ← Python FastAPI (OOP Logic Engine)
│   ├── app/
│   │   ├── abstractions/      ← Abstraksi (ABC)
│   │   ├── encapsulation/     ← Enkapsulasi (@property)
│   │   ├── inheritance/       ← Pewarisan (Physical/Digital Asset)
│   │   ├── polymorphism/      ← Polimorfisme (AssetFactory)
│   │   ├── interfaces/        ← Interface (Loggable, Depreciable)
│   │   ├── strategies/        ← Strategy Pattern (3 metode depresiasi)
│   │   ├── observers/         ← Observer Pattern (Event Dispatcher)
│   │   └── value_objects/     ← Value Object (Audit Trail - frozen)
│   ├── tests/                 ← 46 unit tests
│   └── main.py               ← FastAPI entry point
│
├── vanguard-asset-web/        ← Laravel 11 (Web Frontend)
│   ├── app/
│   │   ├── Http/Controllers/  ← Controllers (Asset, Approval, Auth, dll)
│   │   ├── Models/            ← Eloquent Models
│   │   ├── Services/          ← Business Logic Services
│   │   └── Middleware/        ← RBAC & Security Middleware
│   ├── database/migrations/   ← Database migrations
│   ├── resources/views/       ← Blade templates (Dark Glassmorphism UI)
│   └── routes/web.php         ← Route definitions
│
└── README.md
```

---

## 5 Pilar OOP yang Diimplementasikan

| Pilar | Implementasi | File |
|---|---|---|
| **Abstraksi** | `CompanyAsset(ABC)` — class abstrak dengan `@abstractmethod` | `abstractions/base_asset.py` |
| **Enkapsulasi** | `@property purchase_cost` dengan setter validasi (ValueError jika negatif) | `encapsulation/cost_manager.py` |
| **Pewarisan** | `PhysicalAsset` dan `DigitalAsset` mewarisi `CompanyAsset` | `inheritance/` |
| **Polimorfisme** | `AssetFactory.create_asset()` + override `calculate_depreciation()` | `polymorphism/asset_factory.py` |
| **Interface** | `Loggable(ABC)` + `Depreciable(ABC)` dengan audit trail immutable | `interfaces/` |

### Design Patterns

| Pattern | Implementasi |
|---|---|
| **Strategy Pattern** | 3 algoritma depresiasi: `StraightLineStrategy`, `DecliningBalanceStrategy`, `SumOfYearsStrategy` |
| **Observer Pattern** | `EventDispatcher` + `AuditListener` + `NotificationListener` — event dicatat otomatis |

---

## Troubleshooting

| Masalah | Solusi |
|---|---|
| `Port 8001 already in use` | Ganti port: `--port 8002` dan edit `.env` → `ASSET_ENGINE_URL=http://127.0.0.1:8002` |
| `composer: command not found` | Install Composer dari https://getcomposer.org |
| `ModuleNotFoundError: No module named 'fastapi'` | Jalankan `pip install -r requirements.txt` di folder `vanguard-asset-api` |
| Database connection error | Pastikan MySQL/XAMPP running, dan nama database = `vanguard_asset` |
| `php artisan migrate --seed` gagal | Pastikan database sudah dibuat di phpMyAdmin |
| Tombol "Calculate Depreciation" error | Pastikan FastAPI server (Terminal 1) sedang berjalan |

---

## Menjalankan Unit Tests

```bash
cd vanguard-asset-api
python -m pytest tests/ -v
```

Harusnya muncul **46 passed** ✅

---

**Dibuat dengan ❤️ untuk Proyek UAS OOP**
