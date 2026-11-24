# 05 — API Contract & Endpoint Specification
## Website Resmi & Dashboard Admin AOCC Semarang

**Versi Dokumen:** 2.0 (Single-Origin, Low-Resource, Paginated)  
**Tanggal:** 2026-11-24

---

## 1. Konvensi Umum

### 1.1 Single-Origin & Prefiks
Website publik, dashboard admin custom Figma, dan seluruh endpoint backend berjalan pada **satu domain/origin yang sama** (contoh: `https://aoccsemarang.org`) agar tidak memerlukan konfigurasi CORS lintas domain yang rumit.

- **Website Publik (HTML):** `/`
- **Public JSON Endpoints (Read-only + Contact Form):** `/api/v1/public/...`
- **Admin Auth (Session File + CSRF):** `/admin/login`, `/admin/logout`
- **Admin Protected Endpoints (Session File + CSRF):** `/admin/api/...`

> **Catatan:** Seluruh endpoint `/admin/api/...` menggunakan middleware grup `web` (Laravel Session Cookie + CSRF Token dengan `SESSION_DRIVER=file`), bukan Bearer Token stateless dan bukan Redis.

### 1.2 Header & Aturan Pagination
- Request JSON menyertakan `Accept: application/json`.
- Mutasi session-based (`POST`, `PUT`, `PATCH`, `DELETE`) wajib menyertakan `X-CSRF-TOKEN` atau `X-XSRF-TOKEN`.
- **Pagination Wajib:** Seluruh endpoint daftar panjang menggunakan parameter `?page=1&per_page=15` dengan batas keras server **`per_page` antara `1` sampai `50`** (mencegah query tanpa batas yang menghabiskan RAM VPS 1 GB).

### 1.3 Format Response Standar

**Berhasil (Single Resource):**
```json
{
  "message": "Data berhasil diambil.",
  "data": { }
}
```

**Berhasil (Paginated Collection):**
```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42,
    "last_page": 3
  }
}
```

**Error Standar:**
- `401 Unauthorized` — Belum login pada endpoint `/admin/api/*`.
- `403 Forbidden` — Akun admin berstatus `is_active = false`.
- `404 Not Found` — Data tidak ada atau berstatus draft pada endpoint publik.
- `422 Unprocessable Content` — Validasi FormRequest gagal.
- `429 Too Many Requests` — Rate limit terlampaui (disimpan di `CACHE_STORE=file`).

---

## 2. Public Endpoints (Tanpa Login)

Seluruh endpoint publik **hanya mengembalikan data dengan `is_published = true`** dan membatasi jumlah baris.

### 2.1 Halaman Utama Publik
- `GET /` — Merender halaman one-page scrolling AOCC Semarang.

### 2.2 Ringkasan Data Halaman Utama
- **Endpoint:** `GET /api/v1/public/home`
- **Batas Query:** Semua `site_contents` (ringan, ~15–25 baris), maksimal 6–10 `worship_categories` terpublikasi (`display_order ASC`), maksimal **12 `sermons` terbaru** (`published_at DESC, id DESC`), dan maksimal **12 `events` terpublikasi** (`starts_at ASC`).
- **Response (`200 OK`):**
```json
{
  "data": {
    "site_contents": {
      "general": { "church_name": "AOCC Semarang" },
      "hero": { "hero_title": "...", "hero_subtitle": "..." },
      "about": { "about_title": "...", "about_body": "..." },
      "contact": { "contact_address": "...", "contact_email": "..." },
      "social": { "social_instagram_url": "..." }
    },
    "worship_categories": [
      {
        "id": 1,
        "name": "Kids",
        "slug": "kids",
        "short_description": "...",
        "display_order": 1
      }
    ],
    "sermons": [
      {
        "id": 10,
        "title": "Judul Sermon Terbaru",
        "slug": "judul-sermon-terbaru",
        "speaker_name": "...",
        "summary": "...",
        "image_url": "https://aoccsemarang.org/storage/sermons/cover.webp",
        "external_url": "https://youtube.com/watch?v=...",
        "external_link_label": "Tonton Sermon",
        "published_at": "2026-11-22T09:00:00+07:00"
      }
    ],
    "events": [
      {
        "id": 4,
        "title": "Nama Acara",
        "slug": "nama-acara",
        "summary": "...",
        "starts_at": "2026-12-10T18:30:00+07:00",
        "ends_at": "2026-12-10T21:00:00+07:00",
        "location": "AOCC Semarang",
        "image_url": null,
        "external_url": "https://...",
        "external_link_label": "Info Selengkapnya"
      }
    ]
  }
}
```

### 2.3 Detail Modal Kategori Ibadah
- **Endpoint:** `GET /api/v1/public/worships/{slug}`
- Mengembalikan kategori ibadah + array `contents` berstatus `is_published = true` (termasuk `description` panjang, `schedule_text`, `location`, `image_url`, `external_url`, `external_link_label`).

### 2.4 Daftar & Detail Modal Sermon
- **Endpoint:** `GET /api/v1/public/sermons?page=1&per_page=12` (paginated, maks `per_page=24`, urut `published_at DESC, id DESC`, tanpa parameter kategori).
- **Endpoint:** `GET /api/v1/public/sermons/{slug}` (detail lengkap modal sermon).

### 2.5 Daftar & Detail Modal Acara
- **Endpoint:** `GET /api/v1/public/events?scope=upcoming&page=1&per_page=12` (paginated, maks `per_page=24`, urut `starts_at ASC`).
- **Endpoint:** `GET /api/v1/public/events/{slug}` (detail lengkap modal acara).

### 2.6 Kirim Pesan Hubungi Kami
- **Endpoint:** `POST /api/v1/public/contact-messages`
- **Proteksi:** CSRF + Rate limit (maks 5 pesan / 10 menit / IP) + honeypot opsional (`website` harus kosong).
- **Perilaku Backend (`QUEUE_CONNECTION=sync`):**
  1. Validasi payload via `StoreContactMessageRequest`.
  2. Simpan record ke `contact_messages` (`status = unread`, `email_delivery_status = pending`).
  3. Kirim email notifikasi ke `MAIL_CHURCH_TO_ADDRESS` melalui SMTP Gmail (`MAIL_TIMEOUT=10`) di dalam `try/catch`.
  4. Update `email_delivery_status = sent` (dan `email_sent_at`) atau `failed` (dan `email_error`), **tanpa pernah menghapus pesan dari database**.
- **Validasi Backend:**
  - `sender_name`: `required|string|max:120`
  - `sender_email`: `required|email:rfc|max:150`
  - `sender_phone`: `nullable|string|max:30`
  - `subject`: `required|string|max:200`
  - `message`: `required|string|min:10|max:5000`
- **Response (`201 Created`):**
```json
{
  "message": "Terima kasih, pesan Anda telah kami terima.",
  "data": {
    "id": 15,
    "created_at": "2026-11-24T12:00:00+07:00"
  }
}
```

---

## 3. Admin Authentication Endpoints

| Method | Endpoint | Middleware | Deskripsi |
| :--- | :--- | :--- | :--- |
| `POST` | `/admin/login` | `web`, `guest`, `throttle` (5/menit per `username\|ip`) | Login dengan `username` & `password`. Regenerate session ID saat sukses. Error generik `422` saat gagal. |
| `POST` | `/admin/logout` | `web`, `auth` | Invalidate session & regenerate CSRF token. |
| `GET` | `/admin/api/me` | `web`, `auth`, `active_admin` | Profil admin yang sedang login (`id`, `name`, `username`, `email`, `last_login_at`). |

---

## 4. Admin Protected Content Management Endpoints

Seluruh endpoint di bawah ini wajib melewati middleware `web`, `auth`, dan `active_admin`.

### 4.1 Ringkasan Dashboard
- `GET /admin/api/dashboard` — Ringkasan count (kategori ibadah, sermon published/draft, acara published/draft, pesan `unread`, email `failed`).

### 4.2 Kategori Ibadah (`worship_categories`)
- `GET /admin/api/worship-categories` — Daftar kategori terurut `display_order ASC`.
- `POST /admin/api/worship-categories` — Tambah kategori.
- `PUT /admin/api/worship-categories/{id}` — Perbarui kategori.
- `PATCH /admin/api/worship-categories/reorder` — Ubah urutan tampilan (`display_order`).
- `DELETE /admin/api/worship-categories/{id}` — Hapus kategori.

### 4.3 Konten Ibadah (`worship_contents`)
- `GET /admin/api/worship-contents?worship_category_id=&page=1&per_page=15`
- `POST /admin/api/worship-contents`
- `GET /admin/api/worship-contents/{id}`
- `POST`/`PUT /admin/api/worship-contents/{id}` (`_method=PUT` untuk upload file)
- `DELETE /admin/api/worship-contents/{id}`

### 4.4 Sermon (`sermons`)
- `GET /admin/api/sermons?status=&q=&page=1&per_page=15` (urut `published_at DESC, id DESC`)
- `POST /admin/api/sermons`
- `GET /admin/api/sermons/{id}`
- `POST`/`PUT /admin/api/sermons/{id}`
- `DELETE /admin/api/sermons/{id}`

### 4.5 Acara (`events`)
- `GET /admin/api/events?status=&q=&page=1&per_page=15` (urut `starts_at DESC`)
- `POST /admin/api/events`
- `GET /admin/api/events/{id}`
- `POST`/`PUT /admin/api/events/{id}`
- `DELETE /admin/api/events/{id}`

### 4.6 Pesan Pengunjung (`contact_messages`)
- `GET /admin/api/contact-messages?status=&email_status=&q=&page=1&per_page=15` (urut `created_at DESC`)
- `GET /admin/api/contact-messages/{id}`
- `PATCH /admin/api/contact-messages/{id}/status` (`unread` | `read` | `archived`)
- `POST /admin/api/contact-messages/{id}/resend-email` (kirim ulang notifikasi Gmail bila status sebelumnya `failed`)
- `DELETE /admin/api/contact-messages/{id}`

### 4.7 Konten Situs (`site_contents`)
- `GET /admin/api/site-contents` — Mengembalikan seluruh konten dikelompokkan per `section` (`general`, `hero`, `about`, `contact`, `social`).
- `PUT /admin/api/site-contents/{section}` — Memperbarui konten dalam satu section (mendukung teks panjang, URL, email, atau upload gambar).

---

## 5. Aturan Validasi & Keamanan Upload

- **URL Eksternal:** `nullable|url:http,https|max:2048` (mencegah skema berbahaya seperti `javascript:`).
- **Upload Gambar:** `nullable|image|mimes:jpg,jpeg,png,webp|max:2048` (maks 2 MB; rekomendasikan WebP untuk hemat bandwidth & RAM). File disimpan dengan nama acak di `storage/app/public/{module}/`, **tidak pernah sebagai BLOB di SQLite**.
- **Rentang Waktu Acara:** `ends_at` wajib `after_or_equal:starts_at` bila diisi.
- **Kredensial Gmail:** Tidak pernah dikirim atau terekspos di response API maupun frontend.
