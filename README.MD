# Rendi Setiawan Jodi — 221011400830 — 07TPLP020

# HealthyBite Drinks (PHP Native)

Aplikasi rekomendasi jus & smoothie berbasis web. Pengguna dapat memilih tujuan minuman (Detox, Energy, Diet, Relax), melihat resep, serta menghitung kalori per porsi.

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


## 🖼 Screenshots Aplikasi

Semua file gambar berada di folder:

`/screenshot`


### 🍹 Halaman User

![Halaman Awal](screenshot/Halaman_Awal.jpg)
![Halaman Beranda User](screenshot/Halaman_BerandaUser.jpg)
![Halaman Semua Resep](screenshot/Halaman_Semua_Resep_User.jpg)
![Halaman Resep User](screenshot/Halaman_ResepUser.jpg)
![Halaman Cetak Resep User](screenshot/Halaman_CetakResepUser.jpg)
![Halaman Daftar](screenshot/Halaman_Daftar.jpg)
![Halaman Login](screenshot/Halaman_Login.jpg)


### 🔐 Halaman Admin

![Halaman Beranda Admin](screenshot/Halaman_BerandaAdmin.jpg)
![Halaman Kelola Resep Admin](screenshot/Halaman_KelolaResepAdmin.jpg)
![Halaman Tambah Resep Admin](screenshot/Halaman_TambahResepAdmin.jpg)
![Halaman Edit Resep Admin](screenshot/Halaman_EditResepAdmin.jpg)
![Halaman Edit Password Admin](screenshot/Halaman_EditPasswordAdmin.jpg)
![Halaman Kelola User Admin](screenshot/Halaman_KelolaUserAdmin.jpg)


📸 Semua screenshot diambil dari localhost (XAMPP).  
👨‍💻 Fitur utama: login member & admin, CRUD resep, upload gambar, kategori minuman (detox, energy, diet, relax), sorting, pencarian, dan pembatasan akses resep untuk user terdaftar.
