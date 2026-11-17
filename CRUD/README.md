# SIMRS - Sistem Informasi Manajemen Data Alat Elektromedis

Aplikasi web CRUD (Create, Read, Update, Delete) modern untuk manajemen inventaris alat-alat elektromedis rumah sakit, dilengkapi dengan sistem multi-tema (*Themes Switcher*) dan fitur otentikasi (*Login & Register*).

---

## 🌟 Fitur Utama

1. **Sistem Multi-Tema (Theme Switcher):**
   - 🌿 **Theme 1 (Hopewell Modern):** Tema hijau emerald modern dengan kartu bersih.
   - 🏛️ **Theme 2 (Hopewell Heritage):** Tema editorial klasik dengan aksen emas.
   - 🌙 **Mode Gelap (Dark Mode):** Tampilan malam elegan yang nyaman di mata.
   - Pilihan tema tersimpan otomatis di *Cookie*, *Session*, dan *LocalStorage*.

2. **Sistem Otentikasi & Keamanan:**
   - **Login & Register:** Enkripsi password menggunakan `password_hash` (BCRYPT).
   - **Auth Middleware (`auth_check.php`):** Mencegah akses data tanpa login.
   - **Prepared Statements:** Mencegah celah keamanan SQL Injection.
   - **Validasi Lengkap:** Validasi input pada form registrasi dan form data alat.

3. **Manajemen Data Alat Elektromedis (CRUD):**
   - **Dashboard Statistik:** Ringkasan jumlah alat, merek, ruangan/lokasi, dan tahun pengadaan.
   - **Pencarian & Multi-Filter:** Filter berdasarkan kata kunci pencarian, ruangan/poli, dan merek.
   - **Tambah & Edit Alat:** Form interaktif dengan ikon dan notifikasi.
   - **Hapus Data Aman:** Dilengkapi modal pop-up konfirmasi Bootstrap.

---

## 🛠️ Teknologi yang Digunakan

- **Bahasa Pemrograman:** PHP 8+
- **Database:** MySQL / MariaDB
- **Front-End:** HTML5, CSS3, JavaScript
- **Framework & Library:**
  - Bootstrap 5
  - Bootstrap Icons
  - Hopewell Responsive UI System

---

## 🚀 Cara Menjalankan Proyek

1. **Persiapan Lingkungan:**
   - Pastikan XAMPP sudah terpasang.
   - Jalankan modul **Apache** dan **MySQL** di XAMPP Control Panel.

2. **Penempatan Folder:**
   - Tempatkan folder ini di direktori `htdocs`, contoh: `c:/xampp/htdocs/CRUD`.

3. **Basis Data (Database):**
   - Database `simrs` dan tabel `users` serta `alat` akan **otomatis dibuat** saat aplikasi pertama kali diakses.
   - Skrip SQL manual juga tersedia pada file `database.sql` jika ingin mengimpor via phpMyAdmin.

4. **Akses Aplikasi di Browser:**
   - Buka browser dan kunjungi:
     ```text
     http://localhost/CRUD/login.php
     ```

---

## 🔑 Akun Default Pengujian

| Username | Password | Role |
| :--- | :--- | :--- |
| `admin` | `admin123` | Administrator |

*(Anda juga dapat mendaftarkan akun baru melalui halaman Registrasi).*

---

## 📁 Struktur Direktori

```text
CRUD/
├── assets/                  # File CSS, JS Bootstrap, dan stylesheet tema
├── includes/                # Komponen modular (header, navbar, footer)
├── theme2/                  # Aset gaya Hopewell Heritage
├── add.php                  # Halaman tambah data alat
├── auth_check.php           # Middleware keamanan otentikasi
├── config.php               # Konfigurasi koneksi database & auto-setup
├── database.sql             # Skrip cadangan struktur database & data
├── delete.php               # Handler penghapusan data alat
├── edit.php                 # Halaman ubah data alat
├── index.php                # Dashboard utama dan tabel data alat
├── login.php                # Halaman masuk (login)
├── logout.php               # Handler logout session
├── register.php             # Halaman pendaftaran akun baru
├── set_theme.php            # Endpoint ganti tema via AJAX/Cookie
└── README.md                # Dokumentasi proyek
```
