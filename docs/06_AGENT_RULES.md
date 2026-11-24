# 06 — Agent & Engineering Rules
## Aturan Pengembangan Proyek Website AOCC Semarang

**Versi Dokumen:** 2.0 (Low-Resource 1 GB RAM VPS Optimization)  
**Tanggal:** 2026-11-24

Dokumen ini menjadi pedoman wajib bagi setiap developer maupun AI coding agent yang berkontribusi di repository ini.

---

## 1. Prinsip Utama & Batasan Stack (Non-Negotiable)

> **Prinsip Utama:** AOCC harus berjalan dengan resource minimal (VPS RAM 1 GB), aman, mudah dirawat, dan siap di-upgrade — bukan dibangun dengan teknologi sebanyak mungkin.

1. **Backend:** Laravel 13 dengan PHP 8.3+.
2. **Database Awal:** SQLite (WAL mode, `DB_FOREIGN_KEYS=true`, di luar webroot `public/`, di luar Git), dengan migrasi dan query standar yang kompatibel untuk migrasi ke MySQL/MariaDB.
3. **Konfigurasi Ringan Wajib:** `DB_CONNECTION=sqlite`, `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`.
4. **Frontend:** HTML, CSS, JavaScript statis (build dilakukan di komputer lokal, bukan di VPS). **DILARANG** menambahkan React, Vue, Svelte, Livewire, atau Inertia tanpa persetujuan eksplisit Project Leader.
5. **Dashboard Admin:** Desain custom dari Figma. **DILARANG** memasang Filament, Nova, atau panel admin generator lainnya.
6. **Service yang DILARANG Ditambahkan pada Tahap Awal:**
   - Redis, Memcached, Elasticsearch, MongoDB
   - Docker di VPS produksi 1 GB
   - Laravel Octane, Swoole, RoadRunner
   - Queue worker permanen (`queue:work` / Horizon)
   - Node.js development server di VPS
   - Server database terpisah (selama menggunakan SQLite)
   - Mail server mandiri atau arsitektur microservice
7. **Package Discipline:** Dilarang menambah package Composer/npm baru kecuali terbukti memberikan manfaat konkret dan disetujui Project Leader. Di produksi wajib menjalankan `composer install --no-dev --optimize-autoloader`.

---

## 2. Batasan Domain & Database

1. **Tepat 7 Tabel Domain:** `users`, `worship_categories`, `worship_contents`, `sermons`, `events`, `contact_messages`, `site_contents`. Dilarang menambah tabel `roles`, `permissions`, `media`, atau tabel tambahan tanpa kebutuhan yang jelas dan disetujui.
2. **Aturan Domain Inti:**
   - **3 Admin** dengan hak akses setara; **tidak ada** registrasi publik dan **tidak ada** fitur lupa password tahap awal.
   - **6 Kategori Ibadah** dikelola di `worship_categories` (dapat diurutkan), bukan hardcode di frontend.
   - **Sermon** tidak memiliki kategori dan selalu diurutkan `published_at DESC, id DESC`.
   - **Acara** tidak memiliki sistem pendaftaran, tiket, atau RSVP.
3. **Cross-DB Compatibility:** Hindari sintaks khusus SQLite atau MySQL/MariaDB di business logic maupun migrasi (misal native MySQL `ENUM` atau raw SQL spesifik vendor).
4. **Dilarang BLOB:** Gambar/file disimpan di filesystem (`storage/app/public`), bukan sebagai BLOB di database.
5. **Anti-N+1 & Pagination Wajib:** Seluruh daftar panjang wajib paginated (`per_page <= 50`), endpoint landing wajib dibatasi jumlah itemnya, dan relasi wajib menggunakan *eager loading*.

---

## 3. Aturan Keamanan & Operasional

1. **Zero Secrets & Zero DB Files in Git:** `.env`, password admin, Gmail App Password, dan file `*.sqlite*` **tidak boleh** masuk Git repository.
2. **Provisioning Admin:** Hanya lewat Artisan command aman (`php artisan aocc:provision-admins`) dengan input password tersembunyi dan Bcrypt hash.
3. **Proteksi Endpoint:** Semua endpoint admin (kecuali login) wajib memakai middleware `web`, `auth`, dan pengecekan `is_active = true`. Gunakan satu domain/origin untuk menghindari kerumitan CORS.
4. **Validasi Backend & Anti-Spam:** Semua mutasi wajib lewat `FormRequest`; URL wajib `http/https`; login admin dan form Hubungi Kami wajib dilengkapi rate limiting.
5. **Zero Message Loss pada Hubungi Kami:** Simpan pesan ke `contact_messages` terlebih dahulu sebelum mencoba kirim SMTP Gmail dalam `try/catch`. Jangan menghapus pesan jika SMTP gagal.
6. **Keamanan Data Produksi:** Dilarang menjalankan perintah destruktif (`migrate:fresh`, `DROP TABLE`, overwrite file produksi, atau restore database produksi) tanpa persetujuan eksplisit Project Leader.

---

## 4. Aturan Kualitas Kode & Dokumentasi

1. **Separation of Concerns:** Controller tipis, validasi di `FormRequest`, logika bisnis di `Service`, serialisasi di `JsonResource`, status tetap di PHP `Enum` (`TitleCase`).
2. **Konvensi PHP 8.3+:** Explicit return type & parameter type hints, selalu gunakan kurung kurawal `{}` untuk control structures, gunakan `php artisan make:* --no-interaction`.
3. **Formatting & Testing:** Jalankan `vendor/bin/pint --dirty --format agent` setelah mengubah file PHP, dan tulis Feature Test (PHPUnit) untuk setiap fitur baru.
4. **Sinkronisasi Dokumentasi:** Setiap perubahan arsitektur, skema, atau kontrak API wajib memperbarui dokumen terkait di `docs/01`–`docs/07`.
