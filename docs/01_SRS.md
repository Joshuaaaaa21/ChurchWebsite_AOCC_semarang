# 01 — Software Requirements Specification (SRS)
## Website Resmi & Dashboard Admin AOCC Semarang

**Versi Dokumen:** 2.0 (Low-Resource VPS 1 GB RAM Optimization)  
**Tanggal:** 2026-11-24  
**Status:** Draft Revisi — Menunggu Konfirmasi Project Leader  
**Lingkup Proyek:** Alfa Omega Community Church (AOCC) Semarang

---

## 1. Tujuan Sistem & Batasan Infrastruktur

Sistem Informasi Website Resmi **AOCC Semarang (Alfa Omega Community Church)** dibangun untuk kebutuhan operasional nyata gereja pada infrastruktur terbatas (**VPS produksi dengan RAM 1 GB**), mencakup:

1. **Website Publik (One-Page Scrolling)** sebagai pusat informasi resmi gereja bagi jemaat dan pengunjung baru.
2. **Dashboard Admin Terpisah** (desain custom dari Figma, tanpa Filament) untuk mengelola seluruh konten website secara terpusat oleh tim admin gereja.
3. **Database Terpusat yang Ringan (SQLite awal, siap migrasi ke MySQL/MariaDB)** untuk menyimpan seluruh konten website, pengaturan konten situs, dan pesan pengunjung secara konsisten tanpa membebani RAM dengan server database terpisah.
4. **Sistem Pesan Pengunjung (Hubungi Kami)** yang menyimpan pesan ke database terlebih dahulu, kemudian mengirim notifikasi email ke Gmail resmi gereja via SMTP, serta dapat dilihat dan dikelola di dashboard admin.

### Batasan Infrastruktur Produksi
- **RAM VPS:** 1 GB (prioritas utama: meminimalkan proses permanen & konsumsi memori tanpa mengorbankan keamanan maupun integritas data).
- **Satu Origin/Domain:** Website publik, dashboard admin, dan endpoint backend dilayani dalam satu domain/origin untuk menghindari kompleksitas CORS.
- **Build Frontend di Lokal:** Seluruh asset frontend (HTML/CSS/JS) dibangun di komputer lokal, bukan oleh Node.js dev server di VPS.

---

## 2. Pengguna & Peran (Actors)

| Peran | Deskripsi | Hak Akses |
| :--- | :--- | :--- |
| **Pengunjung Publik** | Jemaat, simpatisan, atau masyarakat umum yang mengakses website. | Membaca konten publik berstatus *published* dan mengirim pesan melalui formulir Hubungi Kami. Tidak memiliki akun. |
| **Admin (3 Akun)** | Pengurus/tim media AOCC Semarang yang telah dibuatkan akun di database. | Ketiga akun memiliki **hak akses setara (penuh)** untuk mengelola seluruh konten website, konten situs, dan pesan pengunjung. |

> **Catatan:** Sistem tidak memiliki registrasi publik, tidak memiliki tabel roles/permissions bertingkat, dan belum membutuhkan fitur lupa password pada tahap awal.

---

## 3. Kebutuhan Fungsional (Functional Requirements)

### 3.1 Website Publik (One-Page Scrolling)

| ID | Kebutuhan | Detail |
| :--- | :--- | :--- |
| **FR-PUB-01** | Struktur satu halaman | Website publik menampilkan 6 section utama dalam satu halaman scrolling: **Beranda**, **Tentang**, **Ibadah**, **Sermon**, **Acara**, dan **Hubungi Kami**. |
| **FR-PUB-02** | Konten Beranda & Tentang | Teks judul, subjudul, deskripsi panjang, gambar (jika diperlukan), dan tautan eksternal pada section Beranda, Tentang, dan info kontak bersumber dari database (`site_contents`). |
| **FR-PUB-03** | Section Ibadah (6 Kategori) | Menampilkan 6 kategori ibadah yang dapat dikelola dan diurutkan dari database: **Kids**, **Teens**, **Youth**, **Ibadah Raya**, **Kaum Pria**, dan **Kaum Wanita**. |
| **FR-PUB-04** | Modal Detail Ibadah | Setiap item kategori ibadah dapat diklik untuk membuka pop-up/modal yang menampilkan informasi detail (teks panjang, jadwal, lokasi, gambar opsional, tautan eksternal opsional). |
| **FR-PUB-05** | Section Sermon (Kartu Geser) | Menampilkan daftar sermon berstatus *published* dalam bentuk kartu geser (*horizontal carousel/slider*), **tanpa kategori**, dengan **sermon terbaru di posisi paling kiri** (`published_at DESC`). Pengambilan data wajib dibatasi/paginated. |
| **FR-PUB-06** | Section Acara | Menampilkan daftar acara berstatus *published* berisi judul, deskripsi panjang, waktu/tanggal, lokasi, gambar opsional, serta tautan eksternal dan label tombol tautan. **Tidak ada** fitur pendaftaran, tiket, kuota, atau RSVP. |
| **FR-PUB-07** | Formulir Hubungi Kami | Pengunjung dapat mengisi dan mengirim pesan melalui formulir. Sistem memvalidasi input di backend, menyimpan pesan ke tabel `contact_messages` terlebih dahulu, lalu mencoba mengirim email notifikasi ke Gmail resmi gereja secara sinkron (`QUEUE_CONNECTION=sync`). |
| **FR-PUB-08** | Filter Status Publikasi | Website publik hanya boleh menampilkan data yang berstatus `is_published = true`. Data draft atau nonaktif tidak boleh bocor ke endpoint/tampilan publik. |

### 3.2 Autentikasi Admin

| ID | Kebutuhan | Detail |
| :--- | :--- | :--- |
| **FR-AUTH-01** | Login Berbasis Session File | Admin login menggunakan `username` dan `password` melalui mekanisme session Laravel (`web` guard, `SESSION_DRIVER=file`). |
| **FR-AUTH-02** | 3 Akun Admin Ter-provision | Tersedia 3 akun admin dengan hak akses setara yang dibuat melalui Artisan command provisioning aman (bukan hardcode di source code). |
| **FR-AUTH-03** | Tanpa Registrasi Publik | Tidak tersedia halaman atau endpoint registrasi akun baru untuk publik. |
| **FR-AUTH-04** | Tanpa Lupa Password (Tahap Awal) | Tidak tersedia fitur reset password mandiri pada tahap awal. Rotasi password dilakukan oleh operator melalui Artisan CLI di server bila diperlukan. |
| **FR-AUTH-05** | Proteksi Endpoint Admin | Seluruh halaman dan endpoint pengelolaan konten wajib dilindungi middleware autentikasi (`auth`) dan pengecekan akun aktif (`is_active = true`). |
| **FR-AUTH-06** | Logout Aman | Logout menginvalidasi session aktif dan meregenerasi CSRF token. |

### 3.3 Dashboard Admin (Pengelolaan Konten)

| ID | Kebutuhan | Detail |
| :--- | :--- | :--- |
| **FR-ADM-01** | Kelola Kategori Ibadah | Admin dapat melihat, memperbarui, mengubah urutan tampilan (`display_order`), serta menampilkan/menyembunyikan kategori ibadah. |
| **FR-ADM-02** | Kelola Konten Ibadah | Admin dapat membuat, melihat, memperbarui, memublikasikan/menyembunyikan, dan menghapus konten modal untuk masing-masing kategori ibadah (teks panjang, jadwal, lokasi, gambar, dan tautan eksternal). |
| **FR-ADM-03** | Kelola Sermon | Admin dapat membuat, melihat (dengan pagination), memperbarui, mengatur tanggal publikasi (`published_at`), mengatur status publikasi (`is_published`), mengelola gambar/tautan eksternal, dan menghapus sermon. |
| **FR-ADM-04** | Kelola Acara | Admin dapat membuat, melihat (dengan pagination), memperbarui, mengatur tanggal & waktu acara, mengatur tautan eksternal & label tombol tautan, mengatur status publikasi, dan menghapus acara. |
| **FR-ADM-05** | Kelola Pesan Pengunjung | Admin dapat melihat daftar pesan masuk (dengan pagination), membaca detail pesan, mengubah status pesan (`unread`, `read`, `archived`), melihat status pengiriman email ke Gmail, memicu kirim ulang email jika sebelumnya gagal, serta menghapus pesan spam. |
| **FR-ADM-06** | Kelola Konten Situs (`site_contents`) | Admin dapat memperbarui konten teks panjang, gambar, informasi kontak, dan tautan eksternal untuk section **Beranda**, **Tentang**, dan **Hubungi Kami**. |

---

## 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)

| Kategori | ID | Spesifikasi |
| :--- | :--- | :--- |
| **Efisiensi Resource** | **NFR-RES-01** | Sistem harus berjalan ringan pada VPS **RAM 1 GB** hanya dengan 2 daemon utama: **Nginx** dan **PHP-FPM (`ondemand`)**, tanpa daemon database terpisah, tanpa Redis, tanpa worker queue permanen, dan tanpa Node.js di VPS. |
| **Keamanan** | **NFR-SEC-01** | Password wajib di-hash menggunakan Bcrypt (minimal 12 rounds). Dilarang menyimpan password plaintext. |
| **Keamanan** | **NFR-SEC-02** | Tidak ada kredensial asli maupun file database SQLite yang boleh masuk ke Git repository. File database SQLite wajib berada di luar direktori publik (`public/`). |
| **Keamanan** | **NFR-SEC-03** | HTTPS wajib di produksi; proteksi CSRF aktif; cookie session menggunakan `HttpOnly`, `SameSite=Lax`, dan `Secure=true` di HTTPS. |
| **Keamanan** | **NFR-SEC-04** | Rate limiting aktif (menggunakan `CACHE_STORE=file`) pada endpoint login admin dan formulir Hubungi Kami. |
| **Keamanan** | **NFR-SEC-05** | Seluruh input dan file upload divalidasi di backend (FormRequest), termasuk validasi URL (`http`/`https` saja), batas ukuran upload gambar, dan pencegahan penyimpanan file sebagai BLOB di database. |
| **Keandalan & Backup** | **NFR-REL-01** | Formulir Hubungi Kami wajib menyimpan pesan ke SQLite terlebih dahulu sebelum mencoba SMTP Gmail. Kegagalan email tidak boleh menghapus pesan. |
| **Keandalan & Backup** | **NFR-REL-02** | Database SQLite wajib memiliki strategi backup berkala yang aman (konsisten untuk WAL via `.backup`) dan disalin ke luar VPS, serta prosedur restore yang teruji. |
| **Performa & Query** | **NFR-PRF-01** | Wajib pagination untuk seluruh daftar panjang, batas jumlah item di landing page, *eager loading* untuk mencegah N+1, dan index hanya pada kolom yang benar-benar dibutuhkan. |
| **Portabilitas / Upgrade** | **NFR-PRT-01** | Migrasi dan query Eloquent wajib kompatibel dengan SQLite dan MySQL/MariaDB (menghindari sintaks khusus vendor) agar migrasi database di masa depan berjalan mulus. |

---

## 5. Batasan & Di Luar Ruang Lingkup (Out of Scope)

- Framework frontend SPA (React, Vue, Inertia, Livewire).
- Panel admin siap pakai (Filament, Nova, dsb.).
- Service berat yang tidak dibutuhkan: Redis, Elasticsearch, MongoDB, Docker di VPS 1 GB, Laravel Octane, queue worker permanen, server database terpisah pada tahap awal, Node.js dev server di VPS, mail server mandiri, atau arsitektur microservice.
- Sistem pendaftaran acara, tiket, pembayaran, atau RSVP.
- Kategorisasi pada Sermon, registrasi publik, tabel roles/permissions, dan tabel media terpisah.

---

## 6. Temuan Audit & Asumsi Terbuka

### 6.1 Temuan Kondisi Repository Saat Ini
- **Laravel 13.17** sudah terinisialisasi di workspace (`composer.json` sudah ramping: hanya `laravel/framework` dan `laravel/tinker` di `require`).
- Terdapat commit lama (`86a8caf`) dengan skema berbeda (`churches`, `about_contents`, `pastors`, `contact_persons`, `daily_verses`, `service_categories`, `services`, `sermons` berkategori, `events`).
- **Environment lokal:** PHP 8.3+ dan Composer belum ditemukan di PATH aktif.
- **VPS Produksi:** Belum dilakukan inspeksi langsung ke VPS 1 GB — perintah pemeriksaan non-destruktif tersedia di [07_LOW_RESOURCE_ARCHITECTURE.md](file:///c:/Users/shion/webaocc/docs/07_LOW_RESOURCE_ARCHITECTURE.md).

### 6.2 Hal yang Perlu Dikonfirmasi Project Leader
1. **Keputusan terhadap Commit Lama `86a8caf`:** Apakah skema legacy akan diganti bersih ke 7 tabel minimal ini (`users`, `worship_categories`, `worship_contents`, `sermons`, `events`, `contact_messages`, `site_contents`), atau ada konten tertentu (misal filosofi BETHEL) yang ingin dimasukkan ke `site_contents`?
2. **Hasil Pemeriksaan VPS 1 GB:** OS, versi PHP, swap, storage, Nginx, dan PHP-FPM yang tersedia di VPS produksi.
3. **Detail Bisnis Ringan:** Jumlah konten modal per kategori ibadah (1 konten atau bisa lebih), field formulir Hubungi Kami, zona waktu `Asia/Jakarta` (WIB), dan lokasi penyimpanan backup off-site yang diinginkan gereja.
