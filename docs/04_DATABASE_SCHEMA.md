# 04 — Dokumentasi Skema Database
## Website Resmi & Dashboard Admin AOCC Semarang

**Versi Dokumen:** 2.0 (Low-Resource SQLite Awal, Siap Migrasi MySQL/MariaDB)  
**Tanggal:** 2026-11-24  
**Database Awal:** SQLite 3 (WAL mode, `foreign_key_constraints = true`, `busy_timeout = 5000`)  
**Target Upgrade:** MySQL 8+ / MariaDB 10.6+ (`InnoDB`, `utf8mb4_unicode_ci`)

---

## 1. Prinsip Rancangan Database Minimal & Cross-DB

Skema database dibatasi hanya pada **7 tabel domain yang benar-benar dibutuhkan**:

| No | Nama Tabel | Fungsi Utama |
| :-: | :--- | :--- |
| 1 | `users` | 3 akun admin dengan hak akses setara (login `username` + `password`). |
| 2 | `worship_categories` | Master 6 kategori ibadah terkelola & dapat diurutkan. |
| 3 | `worship_contents` | Konten detail modal/window untuk setiap kategori ibadah. |
| 4 | `sermons` | Daftar sermon tanpa kategori (urut `published_at DESC`). |
| 5 | `events` | Daftar acara dengan waktu dan tautan eksternal (tanpa RSVP/tiket). |
| 6 | `contact_messages` | Pesan pengunjung Hubungi Kami + status pengiriman email ke Gmail. |
| 7 | `site_contents` | Konten dinamis section Beranda, Tentang, Hubungi Kami, dan sosial media. |

### Aturan Kompatibilitas SQLite ↔ MySQL/MariaDB
- **Tidak menggunakan tipe khusus vendor:** Status seperti `unread|read|archived` menggunakan `string(20)` (divalidasi lewat PHP Enum + FormRequest), bukan native `ENUM` MySQL.
- **Teks panjang standar:** Menggunakan `$table->longText(...)` di Laravel Blueprint (otomatis menjadi `TEXT` di SQLite dan `LONGTEXT` di MySQL/MariaDB).
- **Foreign key aktif penuh:** `DB_FOREIGN_KEYS=true` wajib aktif di SQLite (`PRAGMA foreign_keys = ON` dikelola otomatis oleh Laravel).
- **Tanpa BLOB & tanpa tabel `media`:** Gambar disimpan di filesystem (`storage/app/public/...`), database hanya menyimpan `image_path` relatif.
- **Tanpa tabel operasional ekstra:** Karena memakai `SESSION_DRIVER=file`, `CACHE_STORE=file`, dan `QUEUE_CONNECTION=sync`, tabel `sessions`, `cache`, dan `jobs` tidak dipakai pada operasi harian sehingga menghindari beban write & lock pada SQLite.
- **Tanpa tabel `roles`/`permissions`:** Ketiga admin memiliki hak akses setara.

---

## 2. Detail Struktur Tabel

### 2.1 Tabel `users`

| Kolom | Laravel Blueprint | Tipe SQLite / MySQL | Null | Default | Keterangan |
| :--- | :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | `INTEGER PK` / `BIGINT UNSIGNED AI` | NO | — | Primary Key |
| `name` | `string('name', 120)` | `VARCHAR(120)` | NO | — | Nama tampilan admin |
| `username` | `string('username', 50)->unique()` | `VARCHAR(50)` | NO | — | **UNIQUE.** Dipakai untuk login |
| `email` | `string('email', 150)->nullable()->unique()` | `VARCHAR(150)` | YES | `NULL` | **UNIQUE.** Opsional internal |
| `password` | `string('password')` | `VARCHAR(255)` | NO | — | Hash Bcrypt (min 12 rounds) |
| `is_active` | `boolean('is_active')->default(true)` | `TINYINT(1)` | NO | `1` | Nonaktifkan akses tanpa hapus histori |
| `last_login_at` | `dateTime('last_login_at')->nullable()` | `DATETIME` | YES | `NULL` | Waktu login terakhir |
| `remember_token` | `rememberToken()` | `VARCHAR(100)` | YES | `NULL` | Token remember bawaan Laravel |
| `created_at` / `updated_at` | `timestamps()` | `DATETIME` / `TIMESTAMP` | YES | `NULL` | Timestamps standar |

---

### 2.2 Tabel `worship_categories`

| Kolom | Laravel Blueprint | Null | Default | Keterangan |
| :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | NO | — | Primary Key |
| `name` | `string('name', 100)` | NO | — | `Kids`, `Teens`, `Youth`, `Ibadah Raya`, `Kaum Pria`, `Kaum Wanita` |
| `slug` | `string('slug', 120)->unique()` | NO | — | **UNIQUE.** Identifier modal/API |
| `short_description` | `string('short_description', 255)->nullable()` | YES | `NULL` | Ringkasan kartu sebelum modal dibuka |
| `display_order` | `unsignedSmallInteger('display_order')->default(0)` | NO | `0` | Urutan tampil (`ASC`) |
| `is_published` | `boolean('is_published')->default(true)` | NO | `1` | Status tampil/sembunyi |
| `created_at` / `updated_at` | `timestamps()` | YES | `NULL` | Timestamps |

- **Index:** `idx_worship_categories_pub_order (is_published, display_order)` — dipakai query section Ibadah di halaman publik.
- **Data Awal (Seeder):** `Kids` (1), `Teens` (2), `Youth` (3), `Ibadah Raya` (4), `Kaum Pria` (5), `Kaum Wanita` (6).

---

### 2.3 Tabel `worship_contents`

| Kolom | Laravel Blueprint | Null | Default | Keterangan |
| :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | NO | — | Primary Key |
| `worship_category_id` | `foreignId('worship_category_id')->constrained()->cascadeOnDelete()` | NO | — | FK ke `worship_categories.id` |
| `title` | `string('title', 160)` | NO | — | Judul modal ibadah |
| `summary` | `string('summary', 300)->nullable()` | YES | `NULL` | Ringkasan opsional |
| `description` | `longText('description')` | NO | — | Teks panjang informasi ibadah |
| `schedule_text` | `string('schedule_text', 255)->nullable()` | YES | `NULL` | Jadwal ibadah (misal: `Setiap Minggu, 09:00 WIB`) |
| `location` | `string('location', 255)->nullable()` | YES | `NULL` | Lokasi/ruangan ibadah |
| `image_path` | `string('image_path', 255)->nullable()` | YES | `NULL` | Path file gambar (BUKAN BLOB) |
| `image_alt` | `string('image_alt', 160)->nullable()` | YES | `NULL` | Alt text gambar |
| `external_url` | `string('external_url', 2048)->nullable()` | YES | `NULL` | Tautan eksternal tervalidasi (`http`/`https`) |
| `external_link_label` | `string('external_link_label', 100)->nullable()` | YES | `NULL` | Teks tombol tautan eksternal |
| `display_order` | `unsignedSmallInteger('display_order')->default(0)` | NO | `0` | Urutan konten dalam kategori |
| `is_published` | `boolean('is_published')->default(true)` | NO | `1` | Status publikasi |
| `updated_by` | `foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pengubah terakhir |
| `created_at` / `updated_at` | `timestamps()` | YES | `NULL` | Timestamps |

- **Index:** `idx_worship_contents_cat_pub_order (worship_category_id, is_published, display_order)` — mempercepat relasi & eager loading konten publik per kategori.

---

### 2.4 Tabel `sermons`

| Kolom | Laravel Blueprint | Null | Default | Keterangan |
| :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | NO | — | Primary Key |
| `title` | `string('title', 200)` | NO | — | Judul sermon |
| `slug` | `string('slug', 220)->unique()` | NO | — | **UNIQUE.** Slug modal/endpoint |
| `speaker_name` | `string('speaker_name', 120)->nullable()` | YES | `NULL` | Nama pembicara (opsional) |
| `scripture_reference` | `string('scripture_reference', 120)->nullable()` | YES | `NULL` | Referensi ayat Alkitab (opsional) |
| `summary` | `string('summary', 300)->nullable()` | YES | `NULL` | Ringkasan pendek di kartu geser |
| `description` | `longText('description')->nullable()` | YES | `NULL` | Deskripsi/catatan panjang sermon |
| `image_path` | `string('image_path', 255)->nullable()` | YES | `NULL` | Path thumbnail/cover (BUKAN BLOB) |
| `image_alt` | `string('image_alt', 160)->nullable()` | YES | `NULL` | Alt text thumbnail |
| `external_url` | `string('external_url', 2048)->nullable()` | YES | `NULL` | Tautan eksternal (YouTube/audio) |
| `external_link_label` | `string('external_link_label', 100)->nullable()` | YES | `NULL` | Label tombol tautan |
| `published_at` | `dateTime('published_at')` | NO | — | **Kunci pengurutan:** terbaru paling kiri (`DESC`) |
| `is_published` | `boolean('is_published')->default(false)` | NO | `0` | Status publikasi (default draft) |
| `created_by` | `foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pembuat |
| `updated_by` | `foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pengubah terakhir |
| `created_at` / `updated_at` | `timestamps()` | YES | `NULL` | Timestamps |

- **Index:** `idx_sermons_pub_published_at (is_published, published_at)` — mendukung query `WHERE is_published = 1 ORDER BY published_at DESC LIMIT N`.
- **Tanpa Kategori:** Tidak memiliki kolom kategori sesuai ketentuan domain.

---

### 2.5 Tabel `events`

| Kolom | Laravel Blueprint | Null | Default | Keterangan |
| :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | NO | — | Primary Key |
| `title` | `string('title', 200)` | NO | — | Judul acara |
| `slug` | `string('slug', 220)->unique()` | NO | — | **UNIQUE.** Slug modal/endpoint |
| `summary` | `string('summary', 300)->nullable()` | YES | `NULL` | Ringkasan singkat kartu acara |
| `description` | `longText('description')` | NO | — | Deskripsi lengkap acara |
| `starts_at` | `dateTime('starts_at')` | NO | — | Tanggal & jam mulai acara (WIB) |
| `ends_at` | `dateTime('ends_at')->nullable()` | YES | `NULL` | Tanggal & jam selesai acara (opsional) |
| `location` | `string('location', 255)->nullable()` | YES | `NULL` | Lokasi acara |
| `image_path` | `string('image_path', 255)->nullable()` | YES | `NULL` | Path poster/gambar acara (BUKAN BLOB) |
| `image_alt` | `string('image_alt', 160)->nullable()` | YES | `NULL` | Alt text gambar |
| `external_url` | `string('external_url', 2048)->nullable()` | YES | `NULL` | Tautan eksternal acara |
| `external_link_label` | `string('external_link_label', 100)->nullable()` | YES | `NULL` | Teks tombol tautan eksternal |
| `is_published` | `boolean('is_published')->default(false)` | NO | `0` | Status publikasi (default draft) |
| `created_by` | `foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pembuat |
| `updated_by` | `foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pengubah terakhir |
| `created_at` / `updated_at` | `timestamps()` | YES | `NULL` | Timestamps |

- **Index:** `idx_events_pub_starts_at (is_published, starts_at)`.
- **Tanpa Pendaftaran/RSVP:** Tidak ada kolom kuota, pendaftaran, atau tiket.

---

### 2.6 Tabel `contact_messages`

| Kolom | Laravel Blueprint | Null | Default | Keterangan |
| :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | NO | — | Primary Key |
| `sender_name` | `string('sender_name', 120)` | NO | — | Nama pengunjung |
| `sender_email` | `string('sender_email', 150)` | NO | — | Email pengunjung |
| `sender_phone` | `string('sender_phone', 30)->nullable()` | YES | `NULL` | Nomor HP/WhatsApp (opsional) |
| `subject` | `string('subject', 200)` | NO | — | Subjek pesan |
| `message` | `longText('message')` | NO | — | Isi pesan panjang |
| `status` | `string('status', 20)->default('unread')` | NO | `'unread'` | `unread` \| `read` \| `archived` |
| `read_at` | `dateTime('read_at')->nullable()` | YES | `NULL` | Waktu dibaca admin |
| `read_by` | `foreignId('read_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pembaca pertama |
| `email_delivery_status` | `string('email_delivery_status', 20)->default('pending')` | NO | `'pending'` | `pending` \| `sent` \| `failed` |
| `email_sent_at` | `dateTime('email_sent_at')->nullable()` | YES | `NULL` | Waktu email berhasil dikirim |
| `email_error` | `text('email_error')->nullable()` | YES | `NULL` | Ringkasan error SMTP jika gagal |
| `ip_address` | `string('ip_address', 45)->nullable()` | YES | `NULL` | IP pengirim (anti-spam/audit) |
| `created_at` / `updated_at` | `timestamps()` | YES | `NULL` | Timestamps |

- **Index:**
  - `idx_contact_messages_status_created (status, created_at)` — filter & urutan inbox dashboard admin.
  - `idx_contact_messages_email_status (email_delivery_status)` — memantau email notifikasi yang `failed`.
- **Penyederhanaan v2.0:** Kolom `user_agent` dihapus karena tidak krusial untuk fungsionalitas dan menghemat ruang database.

---

### 2.7 Tabel `site_contents`

Menggantikan nama `site_settings` pada v1.0 agar selaras penuh dengan spesifikasi 7 tabel AOCC Semarang.

| Kolom | Laravel Blueprint | Null | Default | Keterangan |
| :--- | :--- | :-: | :--- | :--- |
| `id` | `id()` | NO | — | Primary Key |
| `section` | `string('section', 50)` | NO | — | `general` \| `hero` \| `about` \| `contact` \| `social` |
| `key` | `string('key', 100)->unique()` | NO | — | **UNIQUE.** Kunci konten (misal: `hero_title`, `about_body`) |
| `label` | `string('label', 120)` | NO | — | Label ramah manusia di dashboard admin |
| `content_type` | `string('content_type', 20)->default('text')` | NO | `'text'` | `text` \| `longtext` \| `url` \| `image` \| `email` |
| `value` | `longText('value')->nullable()` | YES | `NULL` | Nilai konten (teks panjang, URL, atau path gambar) |
| `updated_by` | `foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()` | YES | `NULL` | Admin pengubah terakhir |
| `created_at` / `updated_at` | `timestamps()` | YES | `NULL` | Timestamps |

- **Index:** `idx_site_contents_section_key (section, key)`.

---

## 3. Strategi Query Efisien & Anti-N+1

1. **Batas Data Wajib (No Unbounded Queries):**
   - Halaman utama publik (`GET /` dan `GET /api/v1/public/home`) hanya mengambil maksimal **12 sermon terbaru** dan **12 acara terpublikasi**, serta 6 kategori ibadah (menggunakan `select(...)` kolom yang dibutuhkan saja).
   - Seluruh daftar panjang (`sermons`, `events`, `contact_messages`) wajib menggunakan `paginate($perPage)` dengan batas maksimal `per_page <= 50`.
2. **Eager Loading:** Saat mengambil `worship_categories` beserta `worship_contents`, wajib memakai `with(['publishedContents' => ...])` untuk mencegah N+1 query.
3. **Lokasi File SQLite Aman:**
   - **Lokal:** `database/database.sqlite` (sudah di-ignore oleh `database/.gitignore`).
   - **VPS Produksi:** Di luar webroot `public/` dan di luar direktori Git, misalnya `/var/lib/aocc/aocc.sqlite` (izin file `640` milik `www-data:www-data`).

---

## 4. Pemetaan Perbedaan terhadap Skema Commit Existing (`86a8caf`)

| Aspek | Skema Existing (`86a8caf`) | Rancangan Target v2.0 (7 Tabel) |
| :--- | :--- | :--- |
| **Database** | SQLite lokal (belum dioptimalkan WAL/busy_timeout) | SQLite WAL + FK ON + migrasi standar siap MySQL/MariaDB |
| **Tabel Aktif** | 12 tabel (termasuk `churches`, `about_contents`, `pastors`, `contact_persons`, `daily_verses`, `service_categories`, `services`, `cache`, `jobs`, `sessions`) | **7 tabel domain inti** (`users`, `worship_categories`, `worship_contents`, `sermons`, `events`, `contact_messages`, `site_contents`) |
| **Sermon** | Memiliki `service_category_id` & `speaker_id` | **Tanpa kategori**, urut `published_at DESC` |
| **Pesan Pengunjung** | Belum ada | `contact_messages` (simpan DB dulu + tracking SMTP Gmail) |
| **Session & Cache** | `database` (menulis ke SQLite tiap request) | `file` (nol write lock ke SQLite) |
