# KursusKu

Project praktikum Pemrograman Web III — **Pertemuan 7**.

Prototype PHP pertemuan 2–6 tetap disimpan terpisah (`kursusku-prototype`).
Project ini adalah perpindahan resmi ke Laravel: Route → Controller → Blade.

## Requirement

- PHP 8.3 atau lebih baru
- Composer
- Laravel 13 (sudah tertulis di `composer.json`)
- Laragon / web server lokal
- Node.js hanya jika ingin menjalankan Vite bawaan skeleton. Halaman pertemuan 7 memakai CSS di `public/css`, jadi **tidak wajib** `npm run dev`.

## Cara Menjalankan

1. Salin folder ini ke `C:\laragon\www\kursusku` (atau buka langsung folder `kursusku-pertemuan-7`).
2. Buka terminal pada folder project.
3. Jalankan: `composer install`
4. Salin `.env.example` menjadi `.env`.
5. Jalankan: `php artisan key:generate`
6. Jalankan: `php artisan serve`
7. Buka alamat yang ditampilkan terminal (umumnya `http://127.0.0.1:8000`).

Di Laragon, setelah `composer install` dan `.env` siap, Reload lalu buka `http://kursusku.test` jika virtual host aktif.

## Fitur Milestone 7

- Named route `home`
- `HomeController@index`
- Blade landing page `resources/views/home.blade.php`
- Data kursus dari controller (`$siteName`, `$featured`)
- Asset CSS di `public/css/style.css`
- Gambar hero di `public/images/hero-kursus.jpg`
- Dua commit Git: inisialisasi Laravel, lalu landing page

## Latihan yang sudah diterapkan di kode akhir

1. Course `UI-01` ditambahkan dari controller (eksperimen controller).
2. Course `JS-01` JavaScript Dasar ditambahkan hanya dari controller.
3. Judul bagian course di Blade memakai **Program Belajar Unggulan**.

## Belum Dikerjakan

- Database
- CRUD
- Authentication
- Relasi data
- Layout Blade, Bootstrap, dan Ajax (itu pertemuan 9, project terpisah)

## Struktur yang disentuh

```
kursusku-pertemuan-7/
|-- app/Http/Controllers/HomeController.php
|-- public/css/style.css
|-- public/images/hero-kursus.jpg
|-- resources/views/home.blade.php
|-- routes/web.php
|-- README.md
|-- LANGKAH-PEMBUATAN.md
`-- evidence/week-07/
```

Langkah pengerjaan berurutan ada di [LANGKAH-PEMBUATAN.md](LANGKAH-PEMBUATAN.md).
