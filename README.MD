# Rendi Setiawan Jodi - 221011400830 - 07TPLP020

# HealthyBite Drinks (PHP Native)
Aplikasi rekomendasi jus & smoothie: pilih tujuan (Detox, Energy, Diet, Relax), lihat resep, dan hitung kalori per porsi. Admin bisa kelola resep (CRUD + upload gambar).

## Stack
- PHP 8+, MySQL/MariaDB (PDO), CSS native
- **Tanpa framework**

## Setup (lokal)
1. Buat database `healthy_drinks` lalu **import** `healthy_drinks.sql` via phpMyAdmin.
2. Salin file contoh DB:
   - `copy inc/db.example.php inc/db.php` (Windows)  
     atau `cp inc/db.example.php inc/db.php` (Mac/Linux)
3. Edit `inc/db.php` → sesuaikan `$DB_USER` / `$DB_PASS` (XAMPP: user `root`, password kosong).
4. (Jika belum ada admin) jalankan script reset admin atau SQL insert yang disertakan.
5. Buka `http://localhost/healthy-drinks/`.

## Login Admin
- Username: `admin`
- Password: `admin111`

## Fitur
- Filter per goal + pencarian
- Sortir: Terbaru, Nama A–Z, Nama Z–A (berlaku di Semua/Goal/Search)
- Detail resep + kalkulator porsi
- Admin CRUD + upload gambar + ganti password
