# 📋 Website Absensi Karyawan Sederhana

Aplikasi pencatatan absensi karyawan sederhana untuk keperluan tes teknis **PT Digital Morp Teknologi**. Dibuat menggunakan **Laravel 11** dan menyimpan data ke dalam **MySQL**.

---

## ✨ Fitur

- **Autentikasi Login**
  - Login menggunakan username dan password.
  - Role pengguna: `admin`, `karyawan`.

- **Dashboard**
  - Menampilkan nama pengguna yang sedang login.
  - Tombol **Absen Masuk** dan **Absen Pulang**.
  - Waktu absensi otomatis mengikuti waktu server.

- **Pencatatan Absensi**
  - Data yang dicatat:
    - Nama karyawan
    - Tanggal
    - Waktu masuk
    - Waktu pulang
    - Tipe absensi (`masuk` / `pulang`)
    - Upload bukti (opsional, disimpan di storage)
    - Catatan (opsional)

- **Riwayat Absensi**
  - Tabel riwayat absensi dengan filter tanggal.

- **Validasi**
  - Tidak bisa absen masuk dua kali dalam satu hari.
  - Absen pulang hanya bisa dilakukan jika sudah absen masuk.

---

## ⚙️ Teknologi yang Digunakan

- Laravel 11
- MySQL
- Bootstrap (untuk UI)
- Laravel Seeder (untuk dummy data)
- Laravel Storage (untuk file bukti absensi)

---

## 🧩 Struktur Database

### Tabel: `users`

| Kolom    | Tipe         | Keterangan                  |
|----------|--------------|------------------------------|
| id       | BIGINT       | Primary key                 |
| username | STRING       | Username login              |
| password | STRING       | Password terenkripsi        |
| role     | TINYINT  | Role pengguna (`1=admin`, `2=karyawan`) |

### Tabel: `absensis`

| Kolom         | Tipe      | Keterangan                                                       |
| ------------- | --------- | ---------------------------------------------------------------- |
| id            | BIGINT    | Primary key, auto increment                                      |
| user\_id      | BIGINT    | Foreign key ke tabel `users` (user yang absen), onDelete cascade |
| tanggal       | DATE      | Tanggal absensi                                                  |
| type          | STRING    | Tipe absensi (boleh null)                                        |
| notes         | TEXT      | Catatan absensi (boleh null)                                     |
| bukti         | STRING    | Bukti absensi (misal foto, file) (boleh null)                    |
| waktu\_masuk  | TIME      | Waktu masuk (boleh null)                                         |
| waktu\_pulang | TIME      | Waktu pulang (boleh null)                                        |
| created\_at   | TIMESTAMP | Waktu data dibuat                                                |
| updated\_at   | TIMESTAMP | Waktu data diubah                                                |

---

## 🚀 Cara Instalasi & Menjalankan

1. **Clone Repository**

```git clone <URL_REPOSITORI_ANDA>```
```cd nama-folder-proyek```


2. **Install Dependensi**

```composer install```



3. **Salin File Environment dan Generate Key**

```cp .env.example .env```
```php artisan key:generate```



4. **Konfigurasi Database**

Edit file `.env` dan sesuaikan:

```DB_CONNECTION=mysql```
```DB_HOST=127.0.0.1```
```DB_PORT=3306```
```DB_DATABASE=absensi_karyawan```
```DB_USERNAME=root```
```DB_PASSWORD= # sesuaikan dengan pengaturan lokal Anda```


5. **Migrasi dan Seeder**

```php artisan migrate```
```php artisan db:seed --class=UserSeeder```


6. **Buat Link Storage**

```php artisan storage:link```


7. **Jalankan Server**

```php artisan serve```


Aplikasi akan berjalan di: [http://localhost:8000](http://localhost:8000)

---

## 🧑‍💻 Akun Demo

| Role     | Username | Password |
|----------|----------|----------|
| Admin    | admin    | password |
| Karyawan | user1    | password |

Anda dapat mengubah atau menambahkan pengguna melalui Seeder atau langsung di database.


-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

# 📥 Cara Import File `.drawio` ke Draw.io (Diagrams.net)

File ini berisi diagram **ERD (Entity Relationship Diagram)** atau skema basis data yang dibuat menggunakan draw.io. Ikuti langkah berikut untuk membuka dan mengedit file `.drawio`.

---

## ✅ Langkah-Langkah Import

### 🔗 Opsi 1: Menggunakan Web App (Rekomendasi)
1. Buka situs: [https://app.diagrams.net](https://app.diagrams.net)
2. Pada popup **"Where would you like to save your diagrams?"**, pilih:
   - ✅ `Device` → lalu klik **"Create New Diagram"**
3. Klik menu **File** > **Import From** > **Device...**
4. Pilih file `absensi_karyawan.drawio` dari komputermu.
5. File akan langsung terbuka dan bisa diedit.

---

## 📂 Struktur File
File ini berisi 2 tabel utama:
- `users` — menyimpan data user
- `absensis` — menyimpan data kehadiran user

Relasi sudah ditambahkan antara tabel-tabel tersebut.

---