# 🗺️ WisataDaerah — Website Promosi Wisata dengan Peta Interaktif

Platform promosi wisata daerah modern berbasis Laravel + Leaflet.js. Menampilkan destinasi wisata dengan peta interaktif, marker clustering, filter dinamis, geolocation "wisata terdekat", sistem ulasan dengan moderasi, itinerary otomatis, dan dukungan multibahasa (Indonesia / English).

---

## 📁 Struktur Folder

```
wisata-daerah/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Dashboard, CRUD Destinasi/Kategori/Event, Moderasi Review, User
│   │   │   ├── Api/                # REST API: Map GeoJSON, Nearby, Filter, Search
│   │   │   ├── Auth/               # Login, Register
│   │   │   └── Visitor/            # Home, Destination, Map, Event, Review, Itinerary
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php # Proteksi route admin (role check)
│   │       └── SetLocale.php       # Set locale dari session
│   ├── Models/                     # User, Category, Destination, DestinationImage,
│   │                               # Facility, Review, Event
│   └── Services/
├── database/
│   ├── migrations/                 # 6 migration files (users → events)
│   └── seeders/DatabaseSeeder.php  # Admin, user demo, kategori, fasilitas, sample destinasi
├── resources/
│   ├── lang/
│   │   ├── id/                     # home, nav, dest, map, itinerary, messages, footer
│   │   └── en/                     # (sama, versi Inggris)
│   └── views/
│       ├── admin/                  # layouts/app, dashboard, destinations, reviews, categories, events
│       └── visitor/                # layouts/app, home, destinations, map, events, itinerary
├── routes/
│   ├── web.php                     # Route visitor + admin
│   └── api.php                     # REST API routes
├── bootstrap/app.php               # Registrasi middleware (Admin, SetLocale)
├── composer.json
└── .env.example
```

---

## 🚀 Cara Menjalankan

### 1. Install dependencies
```bash
composer install
npm install
npm run dev
```

### 2. Konfigurasi environment
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` → sesuaikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

### 3. Migrasi & seed database
```bash
php artisan migrate --seed
```

### 4. Storage link (untuk upload foto)
```bash
php artisan storage:link
```

### 5. Jalankan server
```bash
php artisan serve
```
Akses: **http://localhost:8000**

---

## 👤 Akun Default (Seeder)

| Role  | Email              | Password   |
|-------|--------------------|------------|
| Admin | admin@wisata.com   | password   |
| User  | user@wisata.com    | password   |

---

## 🗺️ Fitur Utama

### Sisi Pengunjung
| Fitur | Keterangan |
|-------|-----------|
| **Hero + Search** | Banner promosi, autocomplete search realtime via `/api/destinations/search` |
| **Peta Interaktif** | Leaflet.js + OpenStreetMap, filter AJAX tanpa reload |
| **Marker Clustering** | Plugin `leaflet.markercluster` — rapi saat data banyak |
| **Popup Marker** | Foto, nama, rating bintang, tombol "Lihat Detail" |
| **Filter Dinamis** | Kategori + Wilayah tanpa page reload |
| **Wisata Terdekat** | Geolocation API browser + Haversine SQL formula |
| **Halaman Detail** | Galeri foto (lightbox), fasilitas, peta lokasi, tombol Google Maps |
| **Ulasan & Rating** | Submit review → pending moderasi admin |
| **Itinerary Otomatis** | "1 Hari di [Wilayah X]" dengan slot waktu |
| **Event & Festival** | Daftar event mendatang dengan thumbnail & detail |
| **Multibahasa** | ID / EN — switch via `/lang/{locale}`, disimpan di session |

### Sisi Admin
| Fitur | Keterangan |
|-------|-----------|
| **Dashboard** | Statistik, top destinasi, review pending, distribusi kategori |
| **CRUD Destinasi** | Tambah/edit/hapus, upload thumbnail + galeri, pilih fasilitas |
| **Pin Location** | Klik peta Leaflet → lat/lng otomatis terisi di form |
| **CRUD Kategori** | Nama ID/EN, icon (Font Awesome), warna marker |
| **CRUD Event** | Tanggal, thumbnail, lokasi dengan koordinat opsional |
| **Moderasi Ulasan** | Approve / Reject dengan catatan admin |
| **Manajemen User** | Ubah role, nonaktifkan akun |

---

## 🛠️ Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend | **Laravel 11** (PHP 8.2+) |
| Frontend | **Blade**, **Tailwind CSS** (CDN), Vanilla JS |
| Peta | **Leaflet.js 1.9** + MarkerCluster + OpenStreetMap |
| Database | **MySQL** / PostgreSQL |
| Auth | Laravel built-in (Session) + Role middleware |
| Storage | Laravel Storage + `storage:link` |
| Multibahasa | Laravel `lang/` files, SetLocale middleware |

---

## 🗄️ Skema Database

```
users               → id, name, email, password, role(admin|user), is_active
categories          → id, name, name_en, slug, icon, color, is_active
destinations        → id, category_id, name, name_en, slug, latitude, longitude,
                      description, address, region, thumbnail, open_hours,
                      ticket_price, rating_avg, review_count, view_count,
                      is_popular, is_active
destination_images  → id, destination_id, image_path, caption, sort_order, is_cover
facilities          → id, name, name_en, icon
destination_facilities → destination_id, facility_id (pivot)
reviews             → id, destination_id, user_id, reviewer_name, rating, comment,
                      status(pending|approved|rejected), admin_note, moderated_by
events              → id, destination_id, title, title_en, slug, thumbnail,
                      location, latitude, longitude, start_date, end_date
```

---

## 📌 API Endpoints

| Method | URL | Keterangan |
|--------|-----|-----------|
| GET | `/api/map/markers` | GeoJSON semua marker untuk Leaflet |
| GET | `/api/map/markers?category_id=1&region=Bantul` | Filter marker |
| GET | `/api/destinations/nearby?lat=X&lng=Y&radius=20` | Wisata terdekat (Haversine) |
| GET | `/api/destinations/filter?category_id=X&region=Y` | Filter AJAX (pagination) |
| GET | `/api/destinations/search?q=keyword` | Autocomplete search |

---

## 📝 Lisensi
MIT License — bebas digunakan dan dimodifikasi.
