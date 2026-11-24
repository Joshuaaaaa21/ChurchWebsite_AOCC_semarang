# 07 — Low-Resource Backend & VPS Architecture Guide
## Optimasi Website & Dashboard Admin AOCC Semarang untuk VPS RAM 1 GB

**Versi Dokumen:** 1.0  
**Tanggal:** 2026-11-24  
**Target Infrastruktur:** VPS Produksi dengan RAM 1 GB  
**Prinsip Utama:** Berjalan dengan resource minimal, aman, terukur, mudah di-backup, dan siap di-upgrade — bukan menumpuk teknologi sebanyak mungkin.

---

## 1. Alasan Pemilihan SQLite sebagai Database Awal

Pada VPS dengan **RAM 1 GB**, menjalankan daemon database terpisah seperti MySQL 8 atau MariaDB secara default menghabiskan sekitar **250–450 MB RAM secara permanen**, belum termasuk OS, Nginx, dan PHP-FPM. Untuk profil beban kerja AOCC Semarang:
- **Mayoritas trafik bersifat baca (read-heavy):** Pengunjung membuka halaman one-page dan membaca detail modal Ibadah, Sermon, serta Acara.
- **Aktivitas tulis (write) sangat rendah:** Hanya 3 admin yang memperbarui konten secara berkala, plus formulir Hubungi Kami dari pengunjung.
- **Session & Cache dipindahkan ke `file` driver:** SQLite tidak terbebani operasi tulis di setiap HTTP request.

### Keuntungan Teknis SQLite untuk Tahap Awal
1. **Nol proses daemon database:** Tidak ada proses background yang mengunci RAM terus-menerus.
2. **Latensi sangat rendah:** Pembacaan langsung dari disk lokal dalam proses PHP (tanpa TCP overhead).
3. **Backup & Restore sangat sederhana:** Seluruh database adalah satu berkas logis (beserta file pendamping `-wal`/`-shm` saat WAL aktif) yang mudah dibackup dengan aman menggunakan perintah resmi `sqlite3 .backup`.
4. **Integritas tetap terjaga:** Foreign key diaktifkan penuh (`DB_FOREIGN_KEYS=true`), mode `WAL` memungkinkan pembacaan berjalan paralel dengan penulisan, dan `busy_timeout=5000` mencegah error lock sesaat ketika 2–3 penulisan terjadi bersamaan.

### Pengaturan SQLite yang Direkomendasikan pada Laravel 13 (`config/database.php`)
Laravel 13 secara native sudah mendukung parameter berikut pada koneksi `sqlite`:
- `foreign_key_constraints`: `env('DB_FOREIGN_KEYS', true)`
- `busy_timeout`: `env('DB_SQLITE_BUSY_TIMEOUT', 5000)` (5 detik)
- `journal_mode`: `env('DB_SQLITE_JOURNAL_MODE', 'WAL')`
- `synchronous`: `env('DB_SQLITE_SYNCHRONOUS', 'NORMAL')`
- `transaction_mode`: `'IMMEDIATE'` (agar transaksi tulis antre rapi memakai `busy_timeout`)

### Lokasi & Keamanan File SQLite
- **Wajib di luar webroot `public/`** dan **wajib di luar Git repository**.
- **Rekomendasi lokasi VPS produksi:** `/var/lib/aocc/aocc.sqlite` (milik user web `www-data:www-data`, izin direktori `750`, izin file `640`). Lokasi di luar folder rilis aplikasi juga membuat database aman saat kode aplikasi diperbarui.
- **Lokasi lokal pengembangan:** `database/database.sqlite` (sudah di-ignore di `database/.gitignore` dengan pola `*.sqlite*`).

---

## 2. Service yang Digunakan vs. Sengaja Tidak Digunakan

### 2.1 Service Aktif di VPS Produksi (Hanya 2 Daemon Aplikasi)

| Service | Peran | Alasan Dipakai |
| :--- | :--- | :--- |
| **Nginx** | HTTPS, kompresi HTTP, penyajian langsung asset statis (CSS/JS/WebP), reverse proxy ke PHP-FPM | Sangat hemat RAM (~10–25 MB) dan sangat efisien melayani file statis tanpa membangunkan PHP. |
| **PHP-FPM (`ondemand`)** | Menjalankan Laravel 13 (PHP 8.3+) | Worker hanya dibuat saat ada request dinamis dan otomatis dimatikan saat idle. |
| **Cron (OS bawaan)** | Backup SQLite berkala & housekeeping log/session (jika diperlukan) | Sudah bawaan Linux, hampir nol RAM. |
| **Gmail SMTP (eksternal)** | Mengirim notifikasi pesan Hubungi Kami | Tidak perlu mengurus reputasi IP, DKIM/SPF kompleks, maupun RAM mail server sendiri. |

### 2.2 Service yang Sengaja TIDAK Digunakan pada Tahap Awal

| Service | Alasan Tidak Dipasang di VPS 1 GB |
| :--- | :--- |
| **MySQL / MariaDB Server** | Menyedot ~250–450 MB RAM permanen; belum dibutuhkan pada volume tulis AOCC tahap awal. |
| **Redis / Memcached** | Tidak dibutuhkan hanya untuk session/cache; `file` driver sudah sangat cukup dan nol RAM daemon. |
| **Queue Worker Permanen (`queue:work` / Horizon)** | Menahan 1 proses PHP permanen (~40–60 MB RAM) hanya untuk kirim email kontak yang frekuensinya rendah. |
| **Docker di VPS 1 GB** | Docker daemon + container runtime memakan ~100–200 MB RAM overhead tambahan yang mahal untuk VPS 1 GB. |
| **Laravel Octane** | Dirancang untuk trafik tinggi dengan memori worker persisten; tidak diperlukan dan berisiko menahan RAM lebih lama jika tidak di-tuning ketat. |
| **Node.js / Vite Dev Server di VPS** | Build CSS/JS dilakukan di komputer lokal; hasil build statis yang diunggah ke VPS. |
| **Elasticsearch / MongoDB / Microservices / Mail Server Mandiri** | Over-engineering yang menguras RAM dan menambah beban perawatan tanpa manfaat nyata bagi AOCC. |

---

## 3. Audit Dependency Aplikasi

### 3.1 Production Dependencies (`composer.json` — `require`)
Saat deploy ke VPS produksi, wajib jalankan `composer install --no-dev --optimize-autoloader`:

| Package | Versi | Status | Alasan |
| :--- | :--- | :--- | :--- |
| `php` | `^8.3` | Wajib | Syarat minimum Laravel 13. |
| `laravel/framework` | `^13.17` | Wajib | Core backend, routing, Eloquent, auth session, mail, validasi. |
| `laravel/tinker` | `^3.0` | Opsional (ringan) | Berguna untuk inspeksi darurat via CLI; tidak memakan RAM saat HTTP request berjalan. |

> **Evaluasi:** Tidak ada package tambahan yang berat (tanpa Filament, tanpa Spatie Permission/MediaLibrary, tanpa Predis, tanpa Sanctum/Passport).

### 3.2 Development-Only Dependencies (TIDAK Diinstal di VPS Produksi)
- **PHP (`require-dev`):** `phpunit/phpunit`, `fakerphp/faker`, `mockery/mockery`, `laravel/pint`, `laravel/pail`, `laravel/pao`, `laravel/boost`, `nunomaduro/collision`.
- **Frontend (`package.json` — build di komputer lokal saja):** `vite`, `tailwindcss`, `@tailwindcss/vite`, `laravel-vite-plugin`, `gsap`, `swiper`. Jika saat implementasi frontend custom Figma nanti `gsap` atau `swiper` tidak dipakai, hapus agar ukuran bundle statis semakin kecil.

---

## 4. Perkiraan Budget RAM & Konfigurasi Konservatif VPS 1 GB

> **Penting:** Angka di bawah ini adalah **estimasi awal** (bukan jaminan kapasitas). Setiap nilai wajib diverifikasi dan disetel ulang berdasarkan hasil pengukuran nyata di VPS produksi.

### 4.1 Estimasi Alokasi RAM (Total 1024 MB)
- **OS Linux + systemd + SSH + journald:** ~180–250 MB
- **Nginx (1 master + 1 worker):** ~15–30 MB
- **PHP-FPM Master + OPcache shared memory (64 MB):** ~80–100 MB
- **PHP-FPM Workers (`ondemand`, 0 saat idle; maks 2 saat aktif × ~40–65 MB):** 0 MB (idle) hingga ~130 MB (2 worker aktif)
- **Buffer / Linux Page Cache / Cadangan Lonjakan:** ~400–550 MB (sangat penting agar kernel tidak memicu OOM Killer!)
- **Swap File (direkomendasikan 1 GB di disk SSD):** Berfungsi sebagai sabuk pengaman darurat terhadap lonjakan sesaat, bukan pengganti RAM aktif.

### 4.2 Rekomendasi Konfigurasi PHP-FPM (Konservatif — Mulai dari Nilai Aman)

File pool contoh (`/etc/php/8.3/fpm/pool.d/aocc.conf`):

```ini
[aocc]
user = www-data
group = www-data
listen = /run/php/php8.3-fpm-aocc.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

; Mode ondemand: tidak menyalakan worker sebelum ada request dinamis
pm = ondemand
; Mulai dari 2 worker (ukur RSS per worker sebelum mempertimbangkan naik ke 3-4)
pm.max_children = 2
; Hentikan worker setelah 10 detik idle agar RAM segera kembali ke OS
pm.process_idle_timeout = 10s
; Daur ulang worker setelah 300 request untuk mencegah akumulasi memory leak kecil
pm.max_requests = 300

; Batasi durasi request agar 1 proses lambat tidak menyandera worker
request_terminate_timeout = 30s
```

**Alasan & Cara Mengukur Dampak PHP-FPM:**
- `pm = ondemand` menjaga RAM idle tetap serendah mungkin.
- `pm.max_children = 2` mencegah 2–3 request berat sekaligus memicu OOM Killer di VPS 1 GB.
- **Cara mengukur:** Jalankan `ps --no-headers -o rss,cmd -C php-fpm8.3` saat aplikasi menerima request untuk melihat rata-rata RSS (MB) per worker. Rumus aman: `max_children <= floor(RAM_tersedia_untuk_PHP / RSS_tertinggi_per_worker)`.

### 4.3 Rekomendasi Konfigurasi PHP & OPcache (`/etc/php/8.3/fpm/conf.d/99-aocc.ini`)

```ini
; Mulai di 128M (cukup untuk Laravel 13 + upload/validasi gambar <= 2 MB)
memory_limit = 128M
max_execution_time = 30
max_input_time = 30
upload_max_filesize = 3M
post_max_size = 5M
expose_php = Off

; OPcache wajib aktif untuk mempercepat eksekusi PHP dan menurunkan CPU/RAM per request
opcache.enable = 1
opcache.enable_cli = 0
opcache.memory_consumption = 64
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0
opcache.revalidate_freq = 0
```

> **Catatan `opcache.validate_timestamps = 0`:** Sangat hemat CPU/disk I/O di produksi, tetapi **wajib** melakukan `systemctl reload php8.3-fpm` setiap kali selesai deploy kode baru. Jika ingin lebih fleksibel saat tahap awal, bisa gunakan `validate_timestamps = 1` dan `revalidate_freq = 60` lalu ukur perbedaannya.
> **Cara mengukur OPcache:** Cek hit rate & memory usage lewat script diagnostik lokal/CLI sementara atau `php-fpm` status page internal (hanya localhost) agar memastikan 64 MB tidak penuh (`opcache_full = false`).

### 4.4 Rekomendasi Konfigurasi Nginx (`/etc/nginx/sites-available/aocc`)

Prinsip utama: Nginx melayani seluruh file statis langsung tanpa menyentuh PHP-FPM, membatasi ukuran body upload, mengaktifkan kompresi gzip, menerapkan cache browser untuk asset statis, memblokir akses ke file sensitif, dan mewajibkan HTTPS.

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name aoccsemarang.org www.aoccsemarang.org;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name aoccsemarang.org www.aoccsemarang.org;

    root /var/www/aocc/public;
    index index.php;

    # Sertifikat TLS (misal Let's Encrypt / Certbot)
    ssl_certificate     /etc/letsencrypt/live/aoccsemarang.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/aoccsemarang.org/privkey.pem;

    # Batasi ukuran upload (selaras dengan post_max_size PHP)
    client_max_body_size 5M;
    client_body_timeout 15s;
    send_timeout 15s;

    # Header keamanan dasar
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Kompresi teks (hemat bandwidth, ringan di CPU pada level 5)
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_comp_level 5;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml image/svg+xml;

    # Layani asset statis & gambar upload langsung oleh Nginx + browser caching
    location ~* \.(?:css|js|webp|jpg|jpeg|png|svg|ico|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, max-age=2592000, immutable";
        access_log off;
        try_files $uri =404;
    }

    # Blokir file tersembunyi (.env, .git, .htaccess) dan file sensitif
    location ~ /\.(?!well-known).* {
        deny all;
        return 404;
    }

    location ~* \.(?:sqlite|sqlite3|db|sql|log|bak)$ {
        deny all;
        return 404;
    }

    # Routing Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm-aocc.sock;
        fastcgi_read_timeout 30s;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

> **Optimasi Gambar WebP:** Konversi poster acara, thumbnail sermon, dan gambar section ke format **WebP** (di komputer admin sebelum upload atau via utilitas ringan jika ekstensi GD/Imagick tersedia di PHP) dapat memangkas ukuran gambar 40–70%, mengurangi beban bandwidth VPS dan waktu muat halaman publik.

---

## 5. Perintah Pemeriksaan Kapasitas VPS (Read-Only & Aman)

Sebelum menerapkan konfigurasi apa pun di VPS produksi, jalankan perintah diagnostik **non-destruktif** berikut di server dan kirimkan hasilnya untuk dievaluasi:

```bash
# 1. Sistem Operasi & Kernel
cat /etc/os-release && uname -a

# 2. RAM & Swap (memastikan total RAM 1 GB & status swap)
free -h && swapon --show

# 3. CPU
nproc && lscpu | grep -E "Model name|CPU\(s\):"

# 4. Kapasitas Storage & Inodes
df -h && df -i

# 5. Service yang sedang aktif & pemakaian RAM terbesar saat ini
ps aux --sort=-%mem | head -n 15

# 6. Versi PHP & Ekstensi yang dibutuhkan Laravel 13 + SQLite
php -v
php -m | grep -iE "pdo|sqlite|mbstring|openssl|tokenizer|xml|ctype|fileinfo|curl|bcmath|gd|opcache"

# 7. Ketersediaan Nginx, PHP-FPM, dan sqlite3 CLI (untuk backup aman)
which nginx php-fpm8.3 php-fpm8.4 sqlite3 certbot 2>&1
nginx -v 2>&1
systemctl is-active nginx php8.3-fpm php8.4-fpm 2>&1
```

### Syarat Kompatibilitas Wajib Laravel 13
- **PHP minimal 8.3** (jika VPS hanya memiliki PHP 8.1/8.2, jangan paksakan instalasi Laravel 13 — rekomendasinya adalah menambahkan repositori Ondřej Surý `ppa:ondrej/php` pada Ubuntu/Debian untuk memasang PHP 8.3/8.4 secara bersih, atau meng-upgrade image OS VPS).
- **Ekstensi PHP wajib:** `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`, `curl`, `bcmath`, `opcache` (plus `gd` jika ingin kompresi gambar server-side).

---

## 6. Risiko & Kompromi Setiap Pilihan Arsitektur

| Keputusan | Manfaat Utama | Risiko / Kompromi | Mitigasi |
| :--- | :--- | :--- | :--- |
| **SQLite (awal)** | Hemat ~250–450 MB RAM, nol daemon DB, backup sederhana | Hanya 1 writer aktif pada satu milidetik (`SQLITE_BUSY` bila banyak write serentak); tidak cocok untuk multi-server horizontal | Aktifkan `WAL`, `busy_timeout=5000`, `transaction_mode=IMMEDIATE`, pindahkan session/cache ke `file`, dan tulis migrasi cross-DB agar mudah pindah ke MySQL/MariaDB. |
| **`SESSION_DRIVER=file` & `CACHE_STORE=file`** | Nol write lock ke SQLite, tanpa Redis | Hanya cocok untuk 1 server (single VPS); file session/cache menumpuk jika tidak dibersihkan | Laravel memiliki lottery garbage collection (`[2, 100]`); tambahkan audit ukuran `storage/framework/` berkala. |
| **`QUEUE_CONNECTION=sync`** | Hemat ~40–60 MB RAM (tanpa worker permanen) | Pengunjung menunggu 1–5 detik saat submit form Hubungi Kami ketika PHP menghubungi Gmail SMTP | Simpan ke DB dulu, atur `MAIL_TIMEOUT=10`, tangkap exception agar pesan tidak hilang, dan sediakan tombol *Resend Email* di dashboard admin. |
| **`pm.max_children = 2` (`ondemand`)** | Mencegah VPS 1 GB kehabisan RAM / OOM crash | Request dinamis ke-3+ akan antre singkat di socket Nginx bila 2 worker sedang sibuk | Nginx melayani seluruh file statis (CSS/JS/WebP) tanpa menyentuh PHP; response JSON/Blade harus cepat (<100 ms) + gunakan `config:cache`, `route:cache`, `view:cache`. |
| **VPS RAM 1 GB** | Biaya operasional gereja sangat terjangkau | Ruang margin RAM sempit bila ada lonjakan trafik mendadak atau proses berat (misal `composer install` di VPS) | Jalankan `npm run build` di lokal, sediakan swap 1 GB sebagai pengaman darurat, pasang `composer --no-dev`, dan pantau RAM rutin. |

---

## 7. Strategi Backup & Restore SQLite

> **Peringatan Penting:** Jangan pernah menyalin file SQLite produksi menggunakan `cp` mentah ketika database sedang dipakai dalam mode `WAL` karena dapat menghasilkan salinan yang korup/tidak konsisten. Selalu gunakan perintah `.backup` bawaan `sqlite3`!

### 7.1 Backup Berkala (Konsisten + Terkompresi + Off-Site)
1. **Backup Lokal Harian (Cron pukul 02:00 WIB):**
   ```bash
   # Contoh skrip backup (disimpan di /usr/local/bin/aocc-backup.sh, BUKAN dijalankan sebelum disetujui)
   TIMESTAMP=$(date +%Y%m%d_%H%M%S)
   BACKUP_DIR="/var/backups/aocc"
   DB_FILE="/var/lib/aocc/aocc.sqlite"

   mkdir -p "$BACKUP_DIR"
   sqlite3 "$DB_FILE" ".timeout 5000" ".backup '${BACKUP_DIR}/aocc_${TIMESTAMP}.sqlite'"
   gzip -9 "${BACKUP_DIR}/aocc_${TIMESTAMP}.sqlite"

   # Retensi lokal: simpan 14 hari terakhir agar disk VPS tidak penuh
   find "$BACKUP_DIR" -name "aocc_*.sqlite.gz" -mtime +14 -delete
   ```
2. **Salinan Off-Site (Wajib di Luar VPS):**
   - Jika VPS rusak/terhapus, backup lokal ikut hilang. Salin arsip `.sqlite.gz` (dan folder gambar `storage/app/public/` secara inkremental) ke lokasi di luar VPS, misalnya:
     - **Google Drive gereja / Cloudflare R2 / Backblaze B2** menggunakan `rclone` (ringan, hanya jalan saat cron, bukan daemon permanen), ATAU
     - **Unduh otomatis via `scp`/`rsync`** dari komputer admin/server cadangan.
   - Enkripsi arsip backup (misal `rclone crypt` atau GPG) karena berisi data pesan jemaat (`contact_messages`) dan hash akun admin (`users`).

### 7.2 Prosedur Restore Database (Dengan Pengaman)
**Jangan pernah menimpa database produksi tanpa persetujuan eksplisit dan tanpa membuat cadangan file saat ini terlebih dahulu.**

1. Aktifkan mode maintenance sementara: `php artisan down`.
2. Dekompresi file backup terverifikasi ke file sementara dan uji integritasnya:
   ```bash
   gunzip -c /var/backups/aocc/aocc_YYYYMMDD_HHMMSS.sqlite.gz > /tmp/aocc_restore_test.sqlite
   sqlite3 /tmp/aocc_restore_test.sqlite "PRAGMA integrity_check;"
   ```
   *(Wajib menghasilkan `ok` sebelum melanjutkan!)*
3. Cadangkan file database produksi saat ini (termasuk `-wal` dan `-shm` jika ada) ke folder darurat.
4. Ganti file `/var/lib/aocc/aocc.sqlite` (hapus pasangan `-wal`/`-shm` lama), set kepemilikan `www-data:www-data` dan permission `640`.
5. Jalankan `php artisan up` dan verifikasi halaman publik + login dashboard admin.

---

## 8. Rencana Pengujian & Evaluasi (Sebelum Klaim Kapasitas)

Kita **tidak boleh mengklaim** sistem mampu melayani sekian ratus/ribu pengunjung sebelum pengujian nyata dilakukan. Berikut skenario evaluasi wajib saat tahap implementasi & staging selesai:

| Area Pengujian | Metode Pengukuran | Kriteria Lulus |
| :--- | :--- | :--- |
| **RAM saat Idle** | `free -h` & `ps aux --sort=-%mem` setelah 10 menit tanpa trafik | Tidak ada worker PHP-FPM menggantung; sisa RAM bebas + buffer sehat. |
| **RAM saat Request Bersamaan** | Uji beban ringan bertahap (misal 5, 10, 20 request paralel via `ab`/`wrk`/`hey` dari mesin penguji) sambil memantau `vmstat 1` & `free -m` | Tidak memicu OOM Killer, swap usage tetap minimal, waktu respon stabil. |
| **Performa Website Publik** | Inspeksi query count (Laravel Debugbar/DB query log di lokal) & waktu respon `GET /` dan `/api/v1/public/home` | Nol N+1 query, semua daftar dibatasi, asset statis dilayani langsung Nginx. |
| **CRUD Admin & Proteksi Auth** | Feature Tests (PHPUnit) + uji manual sesi login/logout/rate-limit | Semua endpoint admin menolak unauthenticated (`401`/`302`), validasi menolak input ilegal (`422`). |
| **Contact Form & SMTP Gmail** | Uji kirim sukses + simulasi SMTP gagal/timeout | Pesan tetap tersimpan di `contact_messages` saat SMTP gagal; status `sent`/`failed` akurat; tombol resend berfungsi. |
| **Pagination** | Uji `per_page=15`, `per_page=50`, dan coba `per_page=1000` | Server membatasi keras maksimal `50` item per halaman. |
| **Kestabilan Tulis Bersamaan SQLite** | Uji kirim paralel beberapa pesan kontak + update konten admin serentak | Tidak terjadi error `SQLITE_BUSY` / database locked (berkat `WAL` + `busy_timeout=5000`). |
| **Simulasi Backup & Restore** | Jalankan `.backup`, cek `PRAGMA integrity_check`, lalu restore ke lingkungan staging/uji | Seluruh 7 tabel & relasi pulih utuh 100%. |

---

## 9. Indikator Upgrade & Langkah Migrasi ke MySQL/MariaDB

### 9.1 Indikator Kapan Harus Upgrade VPS atau Pindah ke MySQL/MariaDB
Pertimbangkan upgrade VPS (misal ke RAM 2–4 GB) dan/atau migrasi ke MySQL/MariaDB apabila **salah satu** indikator berikut mulai muncul secara konsisten di log/monitoring:
1. Muncul error `SQLITE_BUSY` / database locked secara berulang meskipun `WAL` dan `busy_timeout` sudah aktif (menandakan frekuensi penulisan serentak mulai tinggi).
2. Rata-rata pemakaian RAM aktif melebihi **75–80%** secara rutin atau swap mulai aktif terus-menerus pada jam ibadah/acara besar.
3. Nginx sering mencatat timeout/antrean pada `php-fpm` karena `pm.max_children = 2` tidak lagi mencukupi lonjakan request dinamis.
4. Ada kebutuhan fitur baru di masa depan yang menuntut background queue worker berat, pencarian full-text skala besar, atau arsitektur lebih dari 1 server web (multi-node).

### 9.2 Langkah Migrasi SQLite → MySQL/MariaDB (Tanpa Mengubah Business Logic)
Karena seluruh migrasi & query Eloquent sudah ditulis secara *cross-database compatible*:
1. Siapkan server/instance MySQL 8+ atau MariaDB 10.6+ (`utf8mb4_unicode_ci`) pada VPS yang sudah di-upgrade atau managed database.
2. Aktifkan mode maintenance (`php artisan down`) dan buat backup final SQLite via `.backup`.
3. Perbarui `.env` menjadi `DB_CONNECTION=mysql` (atau `mariadb`) beserta `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
4. Jalankan `php artisan migrate --force` untuk membentuk 7 tabel kosong dengan schema & foreign key identik di MySQL/MariaDB.
5. Migrasikan data dari SQLite ke MySQL/MariaDB (menggunakan skrip transfer Artisan/chunk Eloquent yang menonaktifkan/mengurutkan FK sesuai dependensi: `users` → `worship_categories` → `worship_contents`, `sermons`, `events`, `contact_messages`, `site_contents`) dan reset auto-increment sequence.
6. Verifikasi jumlah baris per tabel, integritas foreign key, pengujian login admin, serta tampilan website publik.
7. Jalankan `php artisan config:cache` dan `php artisan up`.
