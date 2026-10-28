# Langkah Pembuatan — Pertemuan 9

Lanjutkan project pertemuan 7. Jangan `laravel new` lagi. Tiap langkah di bawah mengubah satu lapisan, lalu diuji sebelum lanjut.

## Langkah 1 — Install Bootstrap, Popper, dan jQuery

Di root project:

```bash
npm install
npm install bootstrap @popperjs/core jquery
npm list bootstrap @popperjs/core jquery
```

Pada zip ini ketiga paket sudah tertulis di `package.json`. Tetap jalankan `npm install` di komputer sendiri.

## Langkah 2 — Hubungkan lewat Vite

`resources/js/bootstrap.js` adalah titik impor yang diminta panduan (`import './bootstrap'`). Skeleton Laravel 13 tidak lagi membuat file itu, jadi file kecil ini ditambahkan agar impor tidak error.

`resources/js/app.js`:

```js
import './bootstrap';
import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;
```

`resources/css/app.css`:

```css
@import 'bootstrap/dist/css/bootstrap.min.css';
```

Jangan menambah CDN Bootstrap/jQuery di layout.

`vite.config.js` pada pertemuan ini tidak memakai plugin Tailwind. Aset UI minggu 9 adalah Bootstrap, sesuai panduan. Tailwind tetap terpasang di skeleton, tetapi tidak diproses agar tidak bentrok dengan CSS Bootstrap.

Jalankan dan biarkan hidup:

```bash
npm run dev
```

## Langkah 3 — Master layout

Buat `resources/views/layouts/app.blade.php`.

- `@vite` memuat CSS dan JS hasil Vite.
- `@include('partials.navbar')` menyisipkan navbar.
- `@yield('content')` adalah ruang isi halaman anak.
- `@yield('title', 'KursusKu')` judul default.
- `session('success')` menampilkan alert setelah form lolos.

## Langkah 4 — Partial navbar

Buat `resources/views/partials/navbar.blade.php`.

Tautan memakai `route('home')` dan `route('enrollments.create')`. Setelah halaman terbuka, kecilkan browser: tombol hamburger harus membuka menu. Jika tidak, cek `import 'bootstrap'`, `npm run dev`, dan `@vite` di layout.

## Langkah 5 — Home memakai layout

Ganti `resources/views/home.blade.php` menjadi `@extends('layouts.app')`. Jangan salin `<html>` dan navbar lagi.

Home tetap menerima `$featured` dari `HomeController` (jejak minggu 7) dan menampilkannya sebagai card Bootstrap. Route `/` tetap mengarah ke controller, tidak dikembalikan ke closure.

## Langkah 6 — Route form

Tambah di `routes/web.php`:

- `GET /pendaftaran` bernama `enrollments.create` — daftar kursus masih array.
- `POST /pendaftaran/preview` bernama `enrollments.preview` — validasi nama, email, course_id, lalu `back()` dengan flash sukses. Belum menyimpan database.

```bash
php artisan route:list
```

## Langkah 7 — Form Blade

Buat `resources/views/enrollments/create.blade.php`.

- `@csrf` wajib pada POST.
- `@foreach ($courses as $id => $course)` mengisi `<select>`.
- `old()` mengembalikan input setelah validasi gagal atau sukses.
- `#availability` adalah tempat teks Ajax.

Eksperimen singkat: hapus `@csrf`, submit, amati error 419, lalu kembalikan `@csrf`.

## Langkah 8 — Route JSON availability

`GET /courses/{course}/availability` bernama `courses.availability`.

Uji tanpa JavaScript dulu:

- `/courses/1/availability` → `{"course":"Web Dasar","remaining":12}`
- `/courses/2/availability` → PHP Dasar, 8
- `/courses/3/availability` → Laravel Dasar, 4
- `/courses/9/availability` → 404

Jangan debug jQuery sebelum JSON ini tampil di browser.

## Langkah 9 — jQuery Ajax

Di `resources/js/app.js`, event `change` pada `#course_id` memanggil `$.ajax` ke URL di atas dan mengganti teks `#availability`.

Prediksi:

| Pilihan | Tampilan |
| --- | --- |
| Kosong | Pilih kursus untuk melihat sisa kursi. |
| Web Dasar | 12 kursi tersisa untuk Web Dasar. |
| PHP Dasar | 8 kursi tersisa untuk PHP Dasar. |
| Laravel Dasar | 4 kursi tersisa untuk Laravel Dasar. |
| Route gagal | Gagal memuat ketersediaan kursus. |

## Langkah 10 — Uji akhir dan Network

1. Beranda: layout + navbar.
2. Pendaftaran: layout yang sama.
3. Pilih kursus: teks berubah, field nama yang sudah diketik tidak hilang.
4. Nama dan email valid: alert sukses.
5. Email salah: validasi menolak.
6. DevTools → Network → Fetch/XHR → `/courses/{id}/availability` status 200, method GET, response JSON, dokumen tidak reload.

Matriks ada di `evidence/week-09/test-matrix.md`. Screenshot Network diambil sendiri saat praktikum.

## Batas minggu ini

Belum ada tabel, Eloquent, login, atau Ajax POST. Sumber angka kursi masih array di route.
