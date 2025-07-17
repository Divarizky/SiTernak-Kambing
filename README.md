# SiTernak Kambing

**SiTernak Kambing** adalah aplikasi dashboard berbasis web untuk memantau dan mengelola data ternak kambing secara real-time. Dirancang khusus untuk kebutuhan peternak agar lebih efisien dalam memantau kesehatan, pertumbuhan kambing.

---

## ✨ Fitur Utama

- 📋 **Manajemen Data Kambing**

  - Tambah, ubah, lihat, dan hapus data kambing
  - Form input lengkap (usia, berat, asal, kandang, kesehatan, dll)

- 🧠 **Monitoring Kesehatan**

  - Indikator status kesehatan kambing
  - Filter otomatis “Perlu Perhatian”

- 📊 **Dashboard Ringkas**

  - Total seluruh kambing
  - Total kambing butuh perhatian

- 🔐 **Login & Akses Admin**
  - Hanya admin yang dapat mengedit, menambah, atau menghapus
  - Redirect otomatis ke halaman login jika belum login

---

## 🛠️ Teknologi Digunakan

- HTML, CSS (Custom)
- JavaScript DOM + Fetch API
- PHP (Native)
- MySQL (Relasional)
- Struktur modular: `views/`, `config/`, `assets/`

---

## 📂 Struktur Direktori

```
/config
    api_sensor.php
    database.php
    db_connect.php
    proses_sensor.php
/views
    auth/forgot_password.php
    auth/login.php
    auth/logout.php
    data_kambing.php
    form_kambing.php
    form_sensor.php
/assets
    /css/style.css
    /js/kambing.js
    /js/sensor.js
index.php
```

---

## 🚀 Cara Menjalankan Proyek

1. Clone repositori ini
2. Buat database dan import db `peternak_kambing.sql`
3. Atur koneksi di `config/db_connect.php`
4. Jalankan lewat browser lokal (`http://localhost/{proyek}/`)

### Login Admin

- Username: `tester`
- Password: `Admin12345`

---

## 📩 Kontak

Pengembang: **Diva Rizky Ananda**  
Email: divarizky28@gmail.com

Pengembang: **Abdurrahman Al-Adalah**  
Email: adilaladalah@gmail.com
