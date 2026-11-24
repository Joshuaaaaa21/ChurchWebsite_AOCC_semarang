# 08 — Requirements, Architecture & Security Audit Report
## Proyek Website Resmi & Dashboard Admin AOCC (Alfa Omega Church Community)

**Tanggal Audit:** 24 November 2026  
**Auditor:** System Architect, Senior Laravel Backend Engineer, Database/API Designer, Security Reviewer & Low-Resource DevOps Reviewer  
**Objek Audit:** `docs/01_SRS.md` s.d. `docs/07_LOW_RESOURCE_ARCHITECTURE.md`, `config/*.php`, `bootstrap/app.php`, `routes/web.php`, `database/migrations/*`, `app/Models/*`, `app/Http/Controllers/*`, `database/seeders/DatabaseSeeder.php`, `composer.json`, `package.json`, serta environment lokal.

> **Klasifikasi Tingkat Keparahan (Severity):**
> - **CRITICAL:** Harus diperbaiki/diputuskan sebelum implementasi kode dimulai.
> - **HIGH:** Sebaiknya diperbaiki sebelum fitur terkait dibuat.
> - **MEDIUM:** Dapat disempurnakan setelah fondasi terbentuk.
> - **LOW:** Peningkatan opsional.

---

## 1. Executive Summary

Secara infrastruktur untuk **VPS RAM 1 GB**, arah arsitektur pada dokumen v2.0 (`SQLite` awal + `Nginx` + `PHP-FPM ondemand` + `SESSION_DRIVER=file` + `CACHE_STORE=file` + `QUEUE_CONNECTION=sync` + build frontend di lokal) sudah berada di jalur yang tepat dan ringan.

Namun, **audit langsung terhadap dokumen dan kode repository menemukan sejumlah celah CRITICAL yang akan menyebabkan pekerjaan ulang (*rework*) jika langsung dilanjutkan ke implementasi CRUD**:

1. **Gap Kebutuhan Bisnis Besar:** Dokumentasi saat ini hanya mencakup **6 section** dan **menghilangkan Section 4 ("Bertumbuh Bersama" — 3 kelompok: Youth, Profesional Muda, Keluarga)**, belum memodelkan sub-konten **Section 2 ("Tentang")** secara memadai (foto & profil pendeta, Prinsip Pelayanan, Information AOC/AOCC, Gerakan Gereja, Narahubung, Media Sosial), serta kekurangan field penting pada **Ibadah** (pemisahan nama lokasi & alamat), **Sermon** (YouTube URL/embed & waktu opsional terpisah dari tanggal), **Acara** (Narahubung hilang), dan **Hubungi Kami** (field *Keperluan* & status wajib/opsional *Nomor handphone*).
2. **Bug Konfigurasi Tersembunyi di Laravel 13:** Lima variabel `.env` penting yang dijanjikan di dokumentasi (`APP_TIMEZONE`, `MAIL_TIMEOUT`, `DB_SQLITE_BUSY_TIMEOUT`, `DB_SQLITE_JOURNAL_MODE`, `DB_SQLITE_SYNCHRONOUS`) **faktanya TIDAK dibaca** oleh `config/app.php`, `config/mail.php`, dan `config/database.php` saat ini.
3. **Konflik Routing/CSRF & Beban Request Modal:** Desain endpoint `/api/v1/public/*` belum selaras dengan `bootstrap/app.php` (tanpa `routes/api.php`) dan memicu request backend pada setiap klik modal, padahal sebagian besar modal bisa menjadi interaksi frontend murni untuk menghemat worker PHP-FPM (`pm.max_children = 2`).
4. **Konflik Identitas & Skema Repository Existing (`86a8caf`):** Terdapat perbedaan nama resmi gereja (**Alfa Omega Church Community** vs *Alfa Omega Community Church*), serta benturan antara 12 tabel prototipe commit `86a8caf` dengan rancangan baru.
5. **Blocker Lokal:** Binary **PHP 8.3+ dan Composer** belum tersedia di PATH mesin lokal, sehingga kode Laravel belum dapat dijalankan/diuji secara lokal.

**Kesimpulan Audit:** **BELUM SIAP masuk implementasi CRUD.** Perlu menyelaraskan spesifikasi & ERD (terutama 7 temuan CRITICAL) serta memulihkan PHP 8.3+ & Composer lokal terlebih dahulu.

---

## 2. Daftar Kebutuhan yang Sudah Terpenuhi

| Area | Fakta Terverifikasi | Referensi File |
| :--- | :--- | :--- |
| **Stack Backend & Frontend** | Laravel 13.17 (`php: ^8.3`), HTML/CSS/JS statis tanpa React/Vue, dashboard custom Figma tanpa Filament. | `composer.json:9-11`, `docs/01_SRS.md:16,96-97`, `docs/06_AGENT_RULES.md:15-19` |
| **Arsitektur VPS RAM 1 GB** | Nginx + PHP-FPM `ondemand`, SQLite awal, `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`, tanpa Redis/Docker/Octane/worker permanen/Node.js di VPS. | `docs/02_ARCHITECTURE.md:13-17`, `docs/07_LOW_RESOURCE_ARCHITECTURE.md:41-61` |
| **Autentikasi 3 Admin Setara** | Login `username` + `password` via session Laravel, tanpa registrasi publik, tanpa tabel roles/permissions, provisioning lewat Artisan command aman tanpa credential di Git. | `docs/01_SRS.md:32-34,57-62`, `docs/02_ARCHITECTURE.md:151-161` |
| **6 Kategori Ibadah Terkelola** | `Kids`, `Teens`, `Youth`, `Ibadah Raya`, `Kaum Pria`, `Kaum Wanita` disimpan di `worship_categories` dengan `display_order` & `is_published`, bukan hardcode. | `docs/03_ERD.dbml:36-51`, `docs/04_DATABASE_SCHEMA.md:53-67` |
| **Sermon Tanpa Kategori & Urut Terbaru Kiri** | Tabel `sermons` tidak memiliki kategori dan memiliki aturan pengurutan terbaru paling kiri (`DESC`). | `docs/03_ERD.dbml:84-108`, `docs/04_DATABASE_SCHEMA.md:115-116` |
| **Acara Tanpa RSVP/Tiket** | Acara menampilkan info + tautan eksternal tanpa pendaftaran, kuota, atau tiket. | `docs/01_SRS.md:49,99`, `docs/04_DATABASE_SCHEMA.md:142` |
| **Alur Email Kontak Anti-Hilang** | Pesan disimpan ke database lebih dahulu sebelum percobaan SMTP Gmail; kegagalan SMTP tidak menghapus pesan dan mencatat `email_delivery_status`. | `docs/02_ARCHITECTURE.md:120-147`, `docs/05_API_CONTRACT.md:134-138` |
| **Perlindungan `.env` & SQLite di Git** | `.gitignore` sudah mengabaikan `.env*`; `database/.gitignore` sudah mengabaikan `*.sqlite*`. | `.gitignore:3-5`, `database/.gitignore:1` |
| **Strategi Backup & Upgrade** | Backup SQLite konsisten menggunakan `.backup` (bukan `cp` mentah), off-site, dan panduan migrasi SQLite → MySQL/MariaDB. | `docs/07_LOW_RESOURCE_ARCHITECTURE.md:278-353` |

---

## 3. Daftar Kebutuhan yang Belum Terpenuhi

| ID | Severity | Kebutuhan Sumber Kebenaran (Bagian A) | Kondisi Saat Ini (Fakta) | Lokasi Bermasalah |
| :--- | :---: | :--- | :--- | :--- |
| **GAP-01** | **CRITICAL** | Website memiliki **7 section**, termasuk **Section 4: Bertumbuh Bersama** (3 kelompok: *Youth*, *Profesional Muda*, *Keluarga*; klik membuka modal berisi informasi, tempat, alamat, waktu, narahubung). | Tidak ada di SRS (hanya tertulis 6 section), tidak ada tabel `growth_groups` di ERD/schema, dan tidak ada endpoint publik/admin di API contract. | `docs/01_SRS.md:44`, `docs/03_ERD.dbml`, `docs/04_DATABASE_SCHEMA.md:13-24`, `docs/05_API_CONTRACT.md` |
| **GAP-02** | **CRITICAL** | **Section 1: Welcome / Welcome Home** — Teks `WELCOME HOME` dan tombol `Jadwal Ibadah` yang mengarahkan ke section Ibadah. | Belum didefinisikan perilaku tombol scroll/anchor frontend murni `Jadwal Ibadah` → `#ibadah`; `site_contents` masih mencontohkan `hero_cta_url` generik. | `docs/01_SRS.md:44-45`, `docs/04_DATABASE_SCHEMA.md:172-187` |
| **GAP-03** | **CRITICAL** | **Section 2: Tentang** — Memuat 8 elemen eksplisit: informasi umum gereja, visi & misi, **foto & informasi pendeta**, **Prinsip Pelayanan**, **Information AOC/AOCC**, **Gerakan Gereja**, **Narahubung**, dan **Media Sosial**. | Hanya disebut generik sebagai `site_contents` (`about_body`). Tidak ada model/struktur jelas untuk profil & foto pendeta, Prinsip Pelayanan, Information AOC/AOCC, Gerakan Gereja, Narahubung lintas-section, maupun daftar Media Sosial. | `docs/01_SRS.md:45`, `docs/04_DATABASE_SCHEMA.md:172-187` |
| **GAP-04** | **CRITICAL** | **Section 3: Ibadah** — Modal berisi informasi tempat, **nama lokasi**, **alamat**, dan **waktu ibadah**. | Tabel `worship_contents` hanya punya 1 kolom `location` dan `schedule_text`; belum memisahkan `venue_name` (nama lokasi) dan `address` (alamat). | `docs/03_ERD.dbml:62-63`, `docs/04_DATABASE_SCHEMA.md:79-80` |
| **GAP-05** | **CRITICAL** | **Section 5: Sermon** — Thumbnail/foto YouTube, Judul, Pembicara, **Tanggal**, **Waktu apabila tersedia**, **URL YouTube**, Ringkasan sermon; klik membuka detail + **video YouTube terhubung**. | Hanya ada `published_at DATETIME NOT NULL` (tidak bisa membedakan waktu opsional vs tengah malam `00:00:00`), `external_url` generik tanpa validasi host YouTube / ekstraksi `youtube_video_id` untuk embed aman, dan `speaker_name` masih `nullable`. | `docs/03_ERD.dbml:84-108`, `docs/04_DATABASE_SCHEMA.md:94-116`, `docs/05_API_CONTRACT.md:88-99` |
| **GAP-06** | **CRITICAL** | **Section 6: Acara** — Kartu (thumbnail, judul, deskripsi singkat, lokasi, waktu) & modal detail (deskripsi lengkap, tautan eksternal, **narahubung**, lokasi, waktu). | **Narahubung tidak ada sama sekali** pada tabel `events` maupun response API acara. | `docs/03_ERD.dbml:113-137`, `docs/04_DATABASE_SCHEMA.md:120-142`, `docs/05_API_CONTRACT.md:101-114` |
| **GAP-07** | **CRITICAL** | **Section 7: Hubungi Kami** — Field: Nama lengkap, **Nomor handphone**, Email, **Keperluan**, Isi pesan. | Kolom dinamai `subject` (bukan `purpose`/Keperluan) dan `sender_phone` diset `nullable` tanpa kejelasan apakah wajib diisi. | `docs/03_ERD.dbml:142-150`, `docs/04_DATABASE_SCHEMA.md:151-155`, `docs/05_API_CONTRACT.md:139-144` |
| **GAP-08** | **HIGH** | Spesifikasi lengkap Request/Response/Validasi/Error untuk seluruh endpoint CRUD Admin. | Sebagian besar endpoint Bagian 4 di `05_API_CONTRACT.md` hanya berupa daftar 1 baris tanpa schema request, response, validasi, maupun error detail. | `docs/05_API_CONTRACT.md:172-213` |
| **GAP-09** | **HIGH** | Pemisahan tegas antara **interaksi frontend murni** vs **request backend** agar tidak membebani VPS 1 GB. | Belum ada matriks tanggung jawab frontend vs backend; desain saat ini justru menyarankan API call terpisah setiap kali klik modal. | `docs/02_ARCHITECTURE.md:20-61`, `docs/05_API_CONTRACT.md:119-130` |

---

## 4. Daftar Konflik Antar Dokumen & Repository

| ID | Severity | Deskripsi Konflik | Pihak yang Bertentangan |
| :--- | :---: | :--- | :--- |
| **CONF-01** | **CRITICAL** | **Nama Resmi Gereja:** Sumber Kebenaran menyebut **AOCC — Alfa Omega Church Community**, sedangkan seluruh dokumen `docs/01`–`07` dan commit `86a8caf` menulis **Alfa Omega Community Church** (serta seeder lama beralamat Jakarta Utara). | Bagian A Prompt vs `docs/01_SRS.md:7,13`, `database/seeders/DatabaseSeeder.php:27,30`, `resources/views/public/landing.blade.php:3,26,101` |
| **CONF-02** | **CRITICAL** | **Jumlah Section & Larangan Kaku "Tepat 7 Tabel":** `docs/06_AGENT_RULES.md:34` melarang menambah tabel di luar 7 tabel, padahal Sumber Kebenaran mensyaratkan Section 4 (*Bertumbuh Bersama*), Profil & Foto Pendeta, serta Narahubung yang digunakan lintas 3 section (*Tentang*, *Bertumbuh Bersama*, *Acara*). | Bagian A Prompt vs `docs/01_SRS.md:44`, `docs/03_ERD.dbml:5`, `docs/04_DATABASE_SCHEMA.md:13`, `docs/06_AGENT_RULES.md:34` |
| **CONF-03** | **CRITICAL** | **Dokumentasi `.env` vs Kode `config/*.php` Aktual:** `docs/02_ARCHITECTURE.md` & `docs/07_LOW_RESOURCE_ARCHITECTURE.md` menyatakan `APP_TIMEZONE`, `MAIL_TIMEOUT`, `DB_SQLITE_BUSY_TIMEOUT`, `DB_SQLITE_JOURNAL_MODE`, `DB_SQLITE_SYNCHRONOUS`, dan `MAIL_CHURCH_TO_ADDRESS` dikontrol via `.env`, tetapi file `config/app.php`, `config/mail.php`, dan `config/database.php` saat ini **tidak membaca variabel-variabel tersebut** (hardcoded `UTC`/`null`/`DEFERRED`). | `docs/02_ARCHITECTURE.md:175,192-194,215,220`, `docs/07_LOW_RESOURCE_ARCHITECTURE.md:26-30` vs `config/app.php:68`, `config/mail.php:48`, `config/database.php:41-44` |
| **CONF-04** | **CRITICAL** | **Endpoint `/api/v1/public/contact-messages` dengan CSRF vs Routing Laravel 13:** `docs/05_API_CONTRACT.md:132-133` mensyaratkan CSRF pada `/api/v1/public/contact-messages`, tetapi `bootstrap/app.php:9-13` belum mendaftarkan `routes/api.php`, dan prefiks `/api/...` secara konvensi stateless (tanpa CSRF). Menempatkan semua `/api/v1/public/*` GET di middleware `web` akan membuat file session baru untuk setiap pengunjung/bot. | `docs/05_API_CONTRACT.md:15,132-133` vs `bootstrap/app.php:9-13` |
| **CONF-05** | **HIGH** | **Batas Pagination Tidak Konsisten:** `docs/05_API_CONTRACT.md:24` menyebut batas keras `per_page` antara `1`–`50`, tetapi baris `124` dan `128` menyebut maks `per_page=24` tanpa membedakan secara formal di aturan umum. | `docs/05_API_CONTRACT.md:24` vs `docs/05_API_CONTRACT.md:124,128` |
| **CONF-06** | **HIGH** | **Upload Gambar via HTTP `PUT`:** `docs/05_API_CONTRACT.md:212` hanya mencantumkan `PUT /admin/api/site-contents/{section}` termasuk untuk upload gambar, padahal PHP tidak mem-parsing `multipart/form-data` pada request `PUT` murni (harus `POST` + `_method=PUT` seperti di baris 186/193/200). | `docs/05_API_CONTRACT.md:186,212` |
| **CONF-07** | **MEDIUM** | **Halaman Arsip Terpisah di Commit `86a8caf` vs Konsep One-Page:** Commit `86a8caf` membuat halaman `/sermon`, `/sermon/{slug}`, `/acara`, `/acara/{slug}` serta tabel `daily_verses` dan intro GSAP, sedangkan Sumber Kebenaran menetapkan website one-page 7 section dengan detail tampil dalam **modal/window**. | `routes/web.php:11-19`, `database/migrations/2026_10_31_081014_create_daily_verses_table.php` vs Bagian A Prompt |

---

## 5. Temuan Bug & Risiko Arsitektur

| ID | Severity | Temuan | Dampak | Lokasi |
| :--- | :---: | :--- | :--- | :--- |
| **ARCH-01** | **CRITICAL** | **Silent No-Op Config Keys (`config/app.php`, `config/mail.php`, `config/database.php`):** Nilai `.env` untuk timezone, SQLite WAL/busy_timeout, dan SMTP timeout tidak terhubung ke file `config/*.php`. | Tanggal acara/sermon meleset 7 jam (UTC), SQLite rentan error `SQLITE_BUSY` tanpa WAL, dan koneksi SMTP yang hang dapat menyandera worker PHP-FPM (`max_children=2`) hingga 30 detik penuh. | `config/app.php:68`, `config/mail.php:48`, `config/database.php:41-44` |
| **ARCH-02** | **HIGH** | **Nginx `add_header` Inheritance Trap:** Di `docs/07_LOW_RESOURCE_ARCHITECTURE.md:193-198`, blok `location` asset statis mendefinisikan `add_header Cache-Control`, sehingga seluruh header keamanan dari blok `server` (`X-Content-Type-Options`, `X-Frame-Options`, `HSTS`) **otomatis hilang** pada response file statis/upload! | File upload di `/storage/` kehilangan proteksi `X-Content-Type-Options: nosniff` dan `HSTS`. | `docs/07_LOW_RESOURCE_ARCHITECTURE.md:179-198` |
| **ARCH-03** | **HIGH** | **Risiko Eksekusi & XSS pada Direktori `/storage/` dan SVG:** Nginx pada `docs/07_LOW_RESOURCE_ARCHITECTURE.md:193` mencantumkan `.svg` di rule static file dan tidak punya blok eksplisit menolak eksekusi `.php` di dalam `/storage/`. | Jika ada celah upload, file `.php` atau `.svg` di `/storage/` bisa dieksekusi atau memicu Stored XSS pada origin yang sama dengan session admin. | `docs/07_LOW_RESOURCE_ARCHITECTURE.md:193-222` |
| **ARCH-04** | **HIGH** | **Pemborosan Worker PHP-FPM oleh Modal API Calls:** Memanggil endpoint PHP baru setiap pengunjung mengklik modal Ibadah/Bertumbuh Bersama/Sermon/Acara tidak efisien untuk VPS RAM 1 GB (`pm.max_children = 2`). | Pada jam ibadah saat banyak jemaat membuka website bersamaan, antrean request PHP-FPM meningkat tanpa manfaat nyata. | `docs/02_ARCHITECTURE.md:20-61`, `docs/05_API_CONTRACT.md:119-130` |
| **ARCH-05** | **MEDIUM** | **Session File Explosion pada Route Publik:** Jika route publik read-only yang diakses bot/crawler melewati middleware `StartSession`, direktori `storage/framework/sessions` dapat menumpuk ribuan file kecil. | Risiko pemborosan inode disk VPS 1 GB. | `docs/05_API_CONTRACT.md:15-19` |
| **ARCH-06** | **MEDIUM** | **`config/queue.php` & `config/app.php` Default ke Tabel `database`:** `QUEUE_FAILED_DRIVER` default `'database-uuids'` dan `APP_MAINTENANCE_STORE` default `'database'`. | Bisa melempar error *no such table* bila suatu saat tersentuh karena tabel `failed_jobs`/`cache` tidak digunakan. | `config/queue.php:124`, `config/app.php:123` |
| **ARCH-07** | **MEDIUM** | **Kebijakan `From:` vs `Reply-To:` pada Email Gmail:** Belum ditegaskan di `docs/02_ARCHITECTURE.md` bahwa `From:` harus akun Gmail SMTP gereja sendiri dan email pengunjung diletakkan di `Reply-To:`. | Gmail SMTP akan menolak atau menandai spam apabila header `From:` dipalsukan ke alamat email pengunjung. | `docs/02_ARCHITECTURE.md:120-147` |

### Pemisahan Tanggung Jawab Backend vs Frontend (Backend Responsibility Matrix)

| Interaksi / Fitur | Jenis Tanggung Jawab | Penjelasan |
| :--- | :--- | :--- |
| Klik tombol `Jadwal Ibadah` di Welcome Home | **Frontend Murni** | Anchor scroll (`#ibadah`), **0 request backend**. |
| Navigasi menu antar 7 section & geser kartu Sermon | **Frontend Murni** | Scroll / carousel JS statis, **0 request backend**. |
| Klik modal Ibadah (6 kategori) & Bertumbuh Bersama (3 kelompok) | **Frontend Murni (data preloaded dari `GET /`)** | Total hanya ~9 item kecil; dikirim sekaligus saat render halaman utama sehingga klik modal instan dan **0 request PHP-FPM**. |
| Klik modal Sermon / Acara (yang sudah tampil di halaman utama) | **Frontend Murni (data preloaded) + Embed YouTube on-click** | Iframe YouTube hanya di-mount saat modal dibuka agar halaman awal cepat; endpoint detail/paginated hanya dipanggil jika pengguna memuat halaman lanjutan (*load more*). |
| Muat halaman utama `GET /` (7 section) | **Pengambilan Data Backend** | Query terindeks + *eager loading* + batas item (misal 12 sermon & 12 acara) + opsional cache file singkat (1–5 menit, otomatis invalidasi saat admin menyimpan konten). |
| Kirim form **Hubungi Kami** | **Backend Write + Email Sinkron** | Validasi FormRequest → simpan ke `contact_messages` → kirim SMTP Gmail (`try/catch`, `timeout=10s`) → catat status pengiriman. |
| Login/Logout Admin & CRUD Dashboard | **Autentikasi, Keamanan & Mutasi Data** | Middleware `web` + `auth` + `active_admin` + CSRF + rate limit + validasi backend + penyimpanan file upload aman. |

---

## 6. Evaluasi ERD, Database & Transisi Repository Existing

### 6.1 Evaluasi Kelayakan Tabel Berdasarkan Sumber Kebenaran

Prinsip: **tidak menambah tabel demi gaya, dan tidak memangkas tabel secara artifisial sampai merusak normalisasi.**

| Tabel | Status Rekomendasi | Alasan Bisnis & Teknis |
| :--- | :--- | :--- |
| `users` | **WAJIB** | 3 akun admin setara (`username`, `password`, `is_active`, `last_login_at`). Tanpa `roles`/`permissions`. |
| `site_contents` | **WAJIB** | Konten tunggal (*singleton*) untuk Welcome Home + Tentang (informasi umum, visi, misi, Prinsip Pelayanan, Information AOC/AOCC, Gerakan Gereja). |
| `pastors` | **DIREKOMENDASIKAN** | Bagian A.2 meminta *foto dan informasi pendeta*. Terpisah agar mendukung 1 atau beberapa pendeta dengan urutan (`display_order`), foto (`photo_path`), dan status publikasi tanpa membebani key-value `site_contents`. *(Jika dipastikan selamanya hanya 1 pendeta, boleh disatukan ke `site_contents` — lihat Bagian 11.)* |
| `contact_persons` | **SANGAT DIREKOMENDASIKAN** | **Narahubung dipakai di 3 section:** Tentang (A.2), Bertumbuh Bersama (A.4), dan Acara (A.6). Satu tabel mencegah duplikasi nomor HP/WhatsApp di 3 tempat dan menjaga integritas via FK `ON DELETE SET NULL`. |
| `social_links` | **OPSIONAL (DIREKOMENDASIKAN RINGAN)** | Bagian A.2 memuat Media Sosial. Jika admin ingin menambah/mengurutkan/menyembunyikan platform dengan mudah dari dashboard, tabel ringan `social_links` lebih rapi; jika daftar platform selalu tetap (misal IG & YouTube saja), cukup di `site_contents`. |
| `worship_categories` | **WAJIB** | 6 kategori Ibadah terkelola & terurut (`Kids`, `Teens`, `Youth`, `Ibadah Raya`, `Kaum Pria`, `Kaum Wanita`). |
| `worship_contents` | **WAJIB (REVISI KOLOM)** | Tambah pemisahan `venue_name` (nama lokasi/tempat) dan `address` (alamat), selain `schedule_text` (waktu ibadah) dan `description` (informasi tempat). |
| `growth_groups` | **WAJIB (TAMBAHAN KRITIS)** | Mewakili Section 4 **Bertumbuh Bersama** (3 kelompok: Youth, Profesional Muda, Keluarga) dengan kolom `name`, `slug`, `short_description`, `description`, `venue_name`, `address`, `schedule_text`, `contact_person_id` (FK), `display_order`, `is_published`. Cukup 1 tabel (bukan 2 tabel) agar tetap sederhana. |
| `sermons` | **WAJIB (REVISI KOLOM)** | Tanpa kategori. Revisi kolom: `speaker_name` (`NOT NULL`), `sermon_date` (`DATE NOT NULL`), `sermon_time` (`TIME NULL`), `sort_published_at` (`DATETIME NOT NULL`, dihitung otomatis dari `sermon_date` + `sermon_time ?? '00:00:00'` untuk index urutan `(is_published, sort_published_at)`), `youtube_url` (`NOT NULL`), `youtube_video_id` (`VARCHAR(20) NOT NULL`), `thumbnail_path` (`NULL` — fallback ke thumbnail YouTube jika kosong), `summary` (`TEXT NOT NULL`), `description` (`LONGTEXT NULL`). |
| `events` | **WAJIB (REVISI KOLOM)** | Tambah `contact_person_id` (FK → `contact_persons.id` `ON DELETE SET NULL`) agar modal detail Acara menampilkan Narahubung sesuai A.6. Tetap tanpa RSVP/tiket. |
| `contact_messages` | **WAJIB (REVISI KOLOM)** | Selaraskan ke 5 field A.7: `sender_name`, `sender_phone`, `sender_email`, `purpose` (Keperluan), `message`, ditambah `status` (`unread`/`read`/`archived`), `read_at`, `read_by`, `email_delivery_status` (`pending`/`sent`/`failed`), `email_sent_at`, `email_error`, `email_retry_count`, `ip_address`. |

> **Evaluasi `softDeletes()`:** **Tidak diperlukan pada tahap awal.** Risiko kehilangan data sudah dimitigasi oleh status `is_published` (konten), `is_active` (admin & narahubung), `status = archived` (pesan), serta backup harian SQLite off-site. Menghindari `softDeletes` juga mencegah konflik `UNIQUE slug` dan menyederhanakan query.
> **Konsistensi SQLite ↔ MySQL/MariaDB:** Seluruh tipe tetap standar (`id()`, `string`, `text`/`longText`, `date`, `time`, `dateTime`, `boolean`, `unsignedSmallInteger`, `foreignId`), tanpa native `ENUM`, tanpa `BLOB`, dan `DB_FOREIGN_KEYS=true`.

### 6.2 Matriks Cakupan CRUD (CRUD Coverage Matrix)

| Entitas / Konten | Create | Read (List & Detail) | Update | Delete / Arsip | Publish / Hide | Urutan (`display_order`) |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: |
| **Konten Situs (`site_contents`)** *(Welcome Home, Tentang, Visi/Misi, Prinsip Pelayanan, Info AOC/AOCC, Gerakan Gereja)* | Seed awal (key tetap) | Ya (per section) | Ya (bulk per section) | Tidak (key inti dilindungi) | N/A | N/A |
| **Pendeta (`pastors`)** | Ya | Ya | Ya (termasuk ganti foto) | Hapus | Ya | Ya |
| **Narahubung (`contact_persons`)** | Ya | Ya | Ya | Nonaktifkan / Hapus (`SET NULL` di relasi) | `is_active` | Ya |
| **Media Sosial (`social_links`)** | Ya | Ya | Ya | Hapus | Ya | Ya |
| **Kategori Ibadah (`worship_categories`)** | Ya | Ya | Ya | Hapus (dengan konfirmasi) | Ya | Ya (reorder) |
| **Konten Ibadah (`worship_contents`)** | Ya | Ya | Ya | Hapus | Ya | Ya |
| **Bertumbuh Bersama (`growth_groups`)** | Ya | Ya | Ya | Hapus | Ya | Ya (reorder) |
| **Sermon (`sermons`)** | Ya | Ya (paginated) | Ya | Hapus | Ya | Otomatis terbaru paling kiri |
| **Acara (`events`)** | Ya | Ya (paginated) | Ya | Hapus | Ya | Otomatis `starts_at` |
| **Pesan Pengunjung (`contact_messages`)** | Publik (Form Hubungi Kami) | Ya (paginated) | Ubah status (`unread`/`read`/`archived`) + Kirim ulang email | Arsip / Hapus spam | N/A | Otomatis terbaru (`created_at DESC`) |
| **Akun Admin (`users`)** | Artisan CLI (`aocc:provision-admins`) | Profil sendiri (`/admin/api/me`) | Rotasi password via Artisan CLI | Nonaktifkan via CLI | `is_active` | N/A |

### 6.3 Perbandingan Repository Existing (`86a8caf`) & Rencana Transisi Aman

| Tabel Existing (`86a8caf`) | Kondisi & Relevansi terhadap Sumber Kebenaran | Rencana Transisi Aman |
| :--- | :--- | :--- |
| `users` | Kurang kolom `username`, `is_active`, `last_login_at`. | Perbarui setelah disetujui (jangan hapus tanpa izin). |
| `churches` & `about_contents` | Berisi data contoh Jakarta Utara & filosofi BETHEL; fungsi digantikan `site_contents` yang lebih fleksibel untuk 8 poin Section Tentang. | Konfirmasi apakah isi teks BETHEL ingin dipindahkan ke key `Prinsip Pelayanan` di `site_contents`. |
| `pastors` | **Relevan** dengan Bagian A.2 (foto & informasi pendeta). | Bisa dipertahankan/disempurnakan (tambah `is_published`). |
| `contact_persons` | **Sangat relevan** (dipakai di Tentang, Bertumbuh Bersama, Acara). | Bisa dipertahankan/disempurnakan (tambah `is_primary`, `is_active`, `display_order`). |
| `daily_verses` | **Tidak ada** di Sumber Kebenaran Bagian A. | Konfirmasi penghapusan/arsip sebelum dipangkas. |
| `service_categories` & `services` | Menggabungkan `ibadah` + `beranda` dalam 1 tabel memakai `enum('group', ...)`. Kurang cocok untuk SQLite ↔ MySQL karena native `enum`, dan tidak memisahkan `venue_name` vs `address`. | Pisahkan rapi menjadi `worship_categories` + `worship_contents` (Ibadah) dan `growth_groups` (Bertumbuh Bersama). |
| `sermons` | Masih punya `service_category_id` (melanggar A.5: sermon tanpa kategori) & tidak punya `sermon_time`. | Sesuaikan ke skema baru tanpa kategori. |
| `events` | Memakai string `start_time`/`end_time`, sudah punya `contact_person_id`. | Standardisasi ke `starts_at`/`ends_at` (`DATETIME`) sambil mempertahankan `contact_person_id`. |
| `cache`, `jobs`, `sessions` | Dibuat oleh migrasi default Laravel `0001_01_01_*`, tetapi tidak dipakai pada konfigurasi `file`/`sync`. | Tidak digunakan secara aktif; aman dibiarkan tidak terisi atau dibersihkan pada tahap reset pra-produksi jika disetujui. |

**Rencana Transisi Aman (Zero Surprise):**
1. Buat branch Git terpisah (misal `feat/backend-v2-audit-aligned`) sebelum menyentuh migrasi/file lama apa pun, sehingga commit `86a8caf` selalu utuh di riwayat Git.
2. Karena belum ada data produksi (baru data dummy seeder Jakarta Utara di `database/database.sqlite` lokal), setelah Project Leader menyetujui skema final, lakukan transisi skema bersih pada branch baru dan verifikasi 100% dengan PHPUnit (`RefreshDatabase`).

---

## 7. Evaluasi API Contract (`docs/05_API_CONTRACT.md`)

| Aspek Audit | Temuan | Severity | Perbaikan yang Dibutuhkan |
| :--- | :--- | :---: | :--- |
| **Kelengkapan Resource** | Belum ada endpoint untuk `growth_groups`, `pastors`, `contact_persons`, dan `social_links`. | **CRITICAL** | Tambahkan seluruh resource tersebut ke kontrak publik (`GET /` / `GET /api/v1/public/home`) dan `/admin/api/*`. |
| **Detail Spesifikasi Endpoint Admin** | Endpoint Bagian 4 tidak menyertakan contoh Request body, Response JSON, aturan validasi per field, dan kode error. | **HIGH** | Lengkapi kontrak request/response/validation/error untuk setiap endpoint CRUD admin sebelum frontend Figma mulai integrasi. |
| **Middleware & CSRF Routing** | `POST /api/v1/public/contact-messages` butuh CSRF, sedangkan GET publik sebaiknya stateless agar tidak memicu pembuatan session file. | **CRITICAL** | Daftarkan `GET /` & `POST /contact-messages` (atau `POST /api/v1/public/contact-messages`) pada grup `web` (CSRF aktif), sementara GET JSON publik opsional dibuat tanpa middleware `StartSession`. |
| **Method Spoofing Upload** | `PUT /admin/api/site-contents/{section}` tidak bisa menerima `multipart/form-data` di PHP. | **HIGH** | Izinkan `POST` dengan `_method=PUT` pada semua endpoint update yang mendukung upload gambar (termasuk `site-contents` dan `pastors`). |
| **Resend Email Safety** | `POST /admin/api/contact-messages/{id}/resend-email` belum memiliki throttle/cooldown dan belum membatasi ulang kirim hanya saat status `failed` (atau dengan konfirmasi). | **HIGH** | Tambahkan throttle (misal maks 3x/menit), cek status `failed` (kembalikan `409 Conflict` jika sudah `sent`), dan catat `email_retry_count`. |
| **Pagination** | Konsistenkan batas `per_page`: misal publik maks `24`, admin maks `50` (default `15`), dituliskan eksplisit di Bagian 1.2. | **MEDIUM** | Perjelas di `docs/05_API_CONTRACT.md`. |

---

## 8. Evaluasi Keamanan (Security Audit)

| Kontrol Keamanan | Status | Temuan & Rekomendasi |
| :--- | :---: | :--- |
| **Password Hashing** | Baik | Bcrypt `BCRYPT_ROUNDS=12`, Eloquent cast `'password' => 'hashed'`, input via `$this->secret()` di Artisan CLI. |
| **Session Authentication** | Baik | `web` guard, `SESSION_DRIVER=file`, `HttpOnly=true`, `SameSite=Lax`, `Secure=true` di produksi HTTPS, `serialization => 'json'` (bawaan Laravel 13), regenerasi session ID saat login, invalidasi + regenerate CSRF saat logout. |
| **CSRF Protection** | **Perlu Perbaikan (CRITICAL)** | Pastikan `POST` form Hubungi Kami dan seluruh mutasi `/admin/*` benar-benar berada di grup middleware `web` dengan `VerifyCsrfToken` aktif. |
| **Rate Limiting** | **Perlu Perbaikan (HIGH)** | Login (`5/menit` per `username|ip`) dan Hubungi Kami (`5/10 menit` per IP) sudah direncanakan, tetapi perlu ditambahkan juga pada `resend-email` dan dilengkapi logging kegagalan login (tanpa mencatat password). |
| **Validasi Backend & SQL Injection** | Baik | Wajib `FormRequest` untuk semua mutasi; gunakan Eloquent/Query Builder berparameter (dilarang raw SQL interpolasi input user). |
| **XSS & Embed YouTube** | **Perlu Perbaikan (HIGH)** | Tegaskan plain-text escaping (`{{ }}` / `textContent`, tanpa `{!! !!}`) untuk semua teks panjang & pesan kontak; validasi ketat URL YouTube (`youtube.com`/`youtu.be`) + ekstrak `youtube_video_id` 11 karakter untuk iframe `youtube-nocookie.com`. |
| **Akses CRUD Tanpa Login** | Baik (secara desain) | Wajib dilindungi `auth` + `EnsureAdminIsActive` dan **wajib diuji dengan PHPUnit Feature Test** untuk setiap endpoint CRUD (401/302 jika unauthenticated). |
| **Perlindungan `.env` & SQLite** | Baik (perlu tambah hardening) | `.env` & `*.sqlite*` di-ignore Git; di VPS, SQLite di `/var/lib/aocc/aocc.sqlite` (`640`), direktori backup `700`. |
| **Penyalahgunaan Contact Form** | **Perlu Perbaikan (HIGH)** | Wajibkan honeypot + minimum form submit time (misal >= 3 detik) + rate limit IP + sanitasi CRLF (`\r\n`) pada nama/keperluan + gunakan `Reply-To` (bukan `From` milik pengunjung). |
| **Kebijakan File Upload** | **Perlu Perbaikan (HIGH)** | Tolak `.svg` dan `.php`; hanya izinkan `jpg,jpeg,png,webp` maks 2 MB; simpan nama file acak (UUID); nonaktifkan eksekusi PHP di Nginx untuk `^~ /storage/`; perbaiki pewarisan `add_header` keamanan di Nginx; hapus file lama saat gambar diganti/item dihapus. |

---

## 9. Evaluasi Keterbatasan VPS RAM 1 GB

| Komponen | Evaluasi | Risiko / Catatan Penting |
| :--- | :---: | :--- |
| **Nginx** | Sangat sesuai (~15–30 MB RAM) | Perbaiki bug `add_header` inheritance pada blok static asset (`docs/07_LOW_RESOURCE_ARCHITECTURE.md:193-198`), hapus `.svg` dari izin upload, dan tambahkan `location ^~ /storage/ { location ~ \.php$ { deny all; } }`. |
| **PHP-FPM (`ondemand`)** | Sangat sesuai (`max_children=2`, idle `10s`) | Wajib ukur RSS nyata per worker di VPS sebelum menaikkan `max_children`. Nilai `request_terminate_timeout=30s` sudah aman. |
| **OPcache** | Sesuai (`64 MB`) | Jika memakai `validate_timestamps=0`, dokumentasikan `systemctl reload php8.3-fpm` sebagai langkah wajib setiap deploy. |
| **SQLite** | Sesuai untuk read-heavy & write rendah | **Wajib** hubungkan `busy_timeout=5000`, `journal_mode=WAL`, `synchronous=NORMAL`, dan `transaction_mode=IMMEDIATE` di `config/database.php` (bukan hanya di `.env`!). |
| **Session & Cache (`file`)** | Sesuai (nol RAM daemon & nol lock SQLite) | Pastikan route GET publik tidak membuat file session baru; pantau jumlah file/inode di `storage/framework/`. |
| **Queue (`sync`) + SMTP Gmail** | Sesuai tanpa worker permanen | **Wajib** hubungkan `MAIL_TIMEOUT=10` di `config/mail.php` agar SMTP lambat tidak menyandera 1 dari 2 worker PHP-FPM. |
| **Backup & Swap** | Baik (perlu dilengkapi) | Tambahkan backup folder gambar (`storage/app/public`) ke paket backup off-site, set permission `/var/backups/aocc` ke `700`, dan pastikan swap 1 GB dikonfirmasi saat pemeriksaan VPS. |
| **Klaim Performa** | Patuh | Tidak ada klaim jumlah pengunjung maksimum; pengujian nyata (idle RAM, concurrent requests, concurrent SQLite writes) tetap menjadi syarat sebelum go-live. |

---

## 10. Evaluasi Environment Lokal vs Kebutuhan Deployment

Pemisahan tegas antara hambatan implementasi lokal dan kebutuhan saat deployment:

### A. Menghambat Implementasi Lokal (Harus Diselesaikan Sekarang)
1. **PHP 8.3+ tidak ditemukan di PATH** (`C:\laragon\bin\php\php-8.3.36-Win32-vs16-x64` sudah tidak ada di disk).
2. **Composer tidak ditemukan di PATH** (`C:\laragon\bin\composer` sudah tidak ada di disk).
3. Ekstensi lokal yang wajib aktif pada PHP lokal nanti: `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl`, `fileinfo`, `xml`, `curl`, `bcmath`.
- *Catatan:* Git (`2.52.0`) dan Node.js (`v24.18.0`) sudah siap di mesin lokal.

### B. Hanya Diperlukan Saat Deployment ke VPS 1 GB (Tidak Menghambat Coding Lokal)
1. Inspeksi kapasitas VPS (`free -h`, `swapon`, `df -h`, OS, PHP 8.3+, Nginx, PHP-FPM, `sqlite3` CLI).
2. Instalasi & tuning Nginx + PHP-FPM + OPcache + HTTPS (Let's Encrypt / Certbot).
3. Pembuatan direktori `/var/lib/aocc/` (database SQLite) & `/var/backups/aocc/` (cron backup + off-site `rclone`/`scp`).
4. Pengaturan App Password Gmail produksi pada `.env` server.

---

## 11. Rekomendasi Perbaikan & Keputusan yang Harus Dibuat Project Leader

### A. Rekomendasi Perbaikan Dokumen & Konfigurasi (Sebelum Coding)
1. Perbarui `docs/01`–`07` ke **Versi 3.0** setelah keputusan di bawah dikunci:
   - Tambahkan **Section 4: Bertumbuh Bersama** (`growth_groups`) dan lengkapi detail **Section 1 (Welcome Home)** & **Section 2 (Tentang)**.
   - Tambahkan `contact_persons` (untuk lintas 3 section) dan `pastors` (bila >1 pendeta / ingin terstruktur), serta revisi kolom `worship_contents`, `sermons`, `events`, dan `contact_messages`.
   - Cabut larangan kaku "tepat 7 tabel" di `docs/06_AGENT_RULES.md`, ganti menjadi prinsip "hanya tabel dengan kebutuhan bisnis tervalidasi".
   - Lengkapi kontrak Request/Response/Validasi/Error untuk seluruh endpoint CRUD admin di `docs/05_API_CONTRACT.md` dan pisahkan interaksi modal frontend murni dari request backend.
   - Perbaiki contoh konfigurasi Nginx (`add_header` inheritance & blokir `.php` di `/storage/`) dan tambahkan backup gambar di `docs/07_LOW_RESOURCE_ARCHITECTURE.md`.
2. Saat mulai implementasi fondasi Laravel, wajib perbaiki `config/app.php` (`timezone`), `config/mail.php` (`timeout` + `church_to`), `config/database.php` (wiring `env()` untuk SQLite WAL/busy_timeout/synchronous/transaction_mode), dan `config/queue.php` (`failed.driver`).

### B. Daftar Keputusan yang Masih Harus Dibuat oleh Project Leader

| No | Pertanyaan Keputusan | Opsi / Rekomendasi Auditor |
| :-: | :--- | :--- |
| **1** | **Nama Resmi Gereja:** Mana penulisan resmi yang benar? | `Alfa Omega Church Community` (Bagian A terbaru) vs `Alfa Omega Community Church` (commit lama & docs v2.0). |
| **2** | **Profil Pendeta (Section 2 Tentang):** Apakah di AOCC hanya ada 1 pendeta atau bisa lebih dari 1 pendeta? | **Rekomendasi:** Gunakan tabel `pastors` (ringan, rapi untuk 1 maupun beberapa pendeta + foto). |
| **3** | **Format Sub-Konten Section 2 Tentang:** Untuk *Prinsip Pelayanan*, *Information AOC/AOCC*, dan *Gerakan Gereja*, apakah masing-masing berupa teks panjang (atau apakah *Prinsip Pelayanan* memiliki daftar poin terstruktur seperti 6 pilar BETHEL di commit lama)? | Perlu konfirmasi agar desain `site_contents` sesuai desain Figma. |
| **4** | **Media Sosial (Section 2 Tentang):** Apakah daftar platform tetap (misal Instagram & YouTube saja) atau admin ingin bisa menambah/mengurutkan platform secara bebas? | Jika tetap: simpan di `site_contents`. Jika dinamis: tambah tabel kecil `social_links`. |
| **5** | **Field Formulir Hubungi Kami:** (a) Apakah **Nomor handphone** wajib atau opsional? (b) Apakah **Keperluan** berupa dropdown pilihan tetap atau input teks bebas? | **Rekomendasi:** Nomor handphone `required` (karena tercantum setara di A.7); Keperluan teks bebas (atau dropdown + "Lainnya" jika sudah ada daftar pilihan). |
| **6** | **Acara Masa Lalu (Section 6 Acara):** Jika semua acara sudah lewat tanggalnya, apakah section Acara menyembunyikan acara lama otomatis atau tetap menampilkan beberapa acara terakhir? | **Rekomendasi:** Default tampilkan acara mendatang (`starts_at >= hari ini`), dengan fallback opsional menampilkan 3 acara terbaru atau pesan "Belum ada acara terjadwal". |
| **7** | **Transisi Commit Lama (`86a8caf`):** Apakah Anda menyetujui pembersihan tabel lama (`churches`, `about_contents`, `daily_verses`, `service_categories`, `services`) dan halaman arsip lama (`/sermon`, `/acara`) di branch baru untuk diganti dengan skema final hasil audit ini? | **Rekomendasi:** Ya, kerjakan di branch baru agar commit `86a8caf` tetap aman di riwayat Git. |

---

## 12. Prioritas Pengerjaan Berikutnya (Roadmap Terstruktur)

1. **Prioritas 1 — Kunci Keputusan & Perbaiki Dokumen (CRITICAL):**
   - Project Leader menjawab 7 pertanyaan keputusan pada Bagian 11.B.
   - Revisi `docs/01`–`07` (v3.0) agar 100% sinkron dengan Sumber Kebenaran (7 section, ERD final, API contract lengkap, perbaikan Nginx & keamanan).
2. **Prioritas 2 — Pulihkan Environment Lokal (CRITICAL Blocker):**
   - Pasang/aktifkan PHP 8.3+ dan Composer di PATH Windows (verifikasi `php -v`, `composer -V`, dan ekstensi `pdo_sqlite`).
3. **Prioritas 3 — Fondasi Backend Laravel di Branch Baru (HIGH):**
   - Perbarui `config/app.php`, `config/database.php`, `config/mail.php`, `config/queue.php`, dan `.env.example`.
   - Buat seluruh migrasi cross-DB final, Eloquent Models, Enum, Factories, dan Seeder data awal (6 kategori Ibadah, 3 kelompok Bertumbuh Bersama, key awal `site_contents` — **tanpa** kredensial admin asli).
   - Buat Artisan command `aocc:provision-admins` + uji dengan PHPUnit.
4. **Prioritas 4 — Implementasi Modul Bertahap & Teruji (HIGH):**
   - Autentikasi session admin + rate limiting + Feature Tests.
   - Modul Hubungi Kami (`contact_messages` + SMTP Gmail + retry aman + anti-spam) + Feature Tests.
   - Modul CRUD konten admin (Site Contents, Pastors, Contact Persons, Worship, Growth Groups, Sermons, Events) + `ImageStorageService` + Feature Tests.
   - Endpoint publik efisien (preloaded `GET /` + paginated fallback).
5. **Prioritas 5 — Audit VPS 1 GB, Integrasi Figma & Uji Beban (MEDIUM):**
   - Jalankan perintah pemeriksaan read-only di VPS 1 GB, verifikasi kompatibilitas PHP 8.3+.
   - Integrasi tampilan HTML/CSS/JS custom Figma (build lokal).
   - Lakukan pengukuran nyata RAM idle, request bersamaan, concurrent SQLite writes, serta simulasi backup & restore sebelum go-live.
