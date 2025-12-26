# Rendi Setiawan Jodi — 221011400830 — 07TPLP020

# HealthyBite Drinks (PHP Native)

Aplikasi rekomendasi jus & smoothie berbasis web. Pengguna dapat memilih tujuan minuman
(Detox, Energy, Diet, Relax), melihat resep, serta menghitung kalori per porsi.

Admin dapat mengelola resep melalui panel dashboard (CRUD + upload gambar).

Pada versi terbaru, akses resep **hanya untuk member yang sudah login**.


## 🛠 Tech Stack

- PHP 8+
- MySQL / MariaDB (PDO)
- CSS Native (tanpa framework)
- PHP Native — **No Framework**


## ⚙️ Instalasi & Setup (Localhost)

1. Buat database:

`healthy_drinks`

2. Import file:

`healthy_drinks.sql`

3. Duplikat file konfigurasi database

Windows:
`copy inc/db.example.php inc/db.php`

Mac / Linux:
`cp inc/db.example.php inc/db.php`

4. Edit file:

`inc/db.php`

Sesuaikan:
- $DB_USER
- $DB_PASS

(Default XAMPP: user root, password kosong)

5. Jika admin belum tersedia → jalankan script reset / insert admin

6. Buka aplikasi di browser:

`http://localhost/healthy-drinks/`


## 🔐 Akun Login

### Admin (Default)

- Username: `admin`
- Password: `admin111`

### User / Member

- Registrasi melalui halaman **Daftar**
- Hanya user login yang dapat mengakses resep


## ✨ Fitur Aplikasi

### 👥 User / Member

- Registrasi & Login
- Akses resep berdasarkan kategori:
  - Detox
  - Energy
  - Diet
  - Relax
- Filter & pencarian resep
- Sorting:
  - Terbaru
  - Nama A–Z
  - Nama Z–A
- Detail resep + kalkulator porsi kalori
- Mode **Cetak Resep**
- Pembatasan akses resep untuk non-member


### 🛠 Admin Panel

- CRUD Resep (Tambah / Edit / Hapus)
- Upload & Update gambar resep
- Kelola user & admin
- Ubah password admin
- Dashboard ringkasan data


## 📸 Screenshots (folder `screenshot/`)

1. Halaman Awal — `Halaman_Awal.jpg`
2. Halaman Beranda User — `Halaman_BerandaUser.jpg`
3. Halaman Resep User — `Halaman_ResepUser.jpg`
4. Cetak Resep — `Halaman_CetakResepUser.jpg`
5. Halaman Daftar — `Halaman_Daftar.jpg`
6. Halaman Login — `Halaman_Login.jpg`
7. Dashboard Admin — `Halaman_BerandaAdmin.jpg`
8. Kelola Resep — `Halaman_KelolaResepAdmin.jpg`
9. Tambah Resep — `Halaman_TambahResepAdmin.jpg`
10. Edit Resep — `Halaman_EditResepAdmin.jpg`
11. Kelola User Admin — `Halaman_KelolaUserAdmin.jpg`

Semua screenshot diambil dari localhost (XAMPP).
