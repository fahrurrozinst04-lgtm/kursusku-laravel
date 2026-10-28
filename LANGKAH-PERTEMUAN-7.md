# Langkah Pembuatan — Pertemuan 7

Kerjakan **berurutan**. Project di folder ini sudah berada di akhir langkah, supaya bisa dijalankan. Bacalah tiap langkah lalu cocokkan dengan file yang berubah. Jangan loncat ke pertemuan 9 sebelum alur Route → Controller → Blade bisa dijelaskan tanpa membaca kode.

## Langkah 0 — Siapkan tools

```bash
php -v
composer -V
git --version
node -v
npm -v
```

PHP harus 8.3 atau lebih baru. Prototype `kursusku-prototype` tidak dihapus.

## Langkah 1 — Buat project Laravel

Dari `C:\laragon\www`:

```bash
composer create-project laravel/laravel kursusku
cd kursusku
php artisan serve
```

Berhenti di sini sampai halaman bawaan Laravel tampil. Zip ini sudah berisi skeleton Laravel 13 tersebut.

Commit pertama (sudah ada di repo):

```bash
git add .
git commit -m "chore: initialize Laravel course project"
```

## Langkah 2 — Route sementara

File: `routes/web.php`

```php
Route::get('/', function () {
    return 'KursusKu berjalan!';
})->name('home');
```

Uji: browser menampilkan teks itu. Lalu `php artisan route:list` dan cari nama `home`.

Closure ini hanya bukti route hidup. Jangan berhenti di sini.

## Langkah 3 — HomeController

```bash
php artisan make:controller HomeController
```

Isi method `index()` dulu dengan `return 'Response dari HomeController';`, lalu ubah route:

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

Uji: browser menampilkan teks dari controller.

## Langkah 4 — Kirim data ke view

Ganti `index()` agar menyiapkan `$siteName` dan array `$featured`, lalu:

```php
return view('home', compact('siteName', 'featured'));
```

`compact` mengirim variabel itu ke Blade. Data tetap di controller, bukan disalin di HTML.

## Langkah 5 — Blade view

Buat `resources/views/home.blade.php`.

- `{{ $siteName }}` menampilkan teks dengan escaping.
- `@foreach ($featured as $course)` mengulang array.
- `$course['name']` membaca satu elemen.

Uji: tiga course awal tampil. Ubah nama salah satu course di controller, refresh, pastikan Blade tidak perlu diubah.

## Langkah 6 — Aset publik

```
public/css/style.css
public/images/hero-kursus.jpg
```

Gambar hero disalin dari prototype. Di Blade:

```blade
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<img src="{{ asset('images/hero-kursus.jpg') }}" alt="Ilustrasi kelas KursusKu">
```

`asset()` membuat URL dari folder `public`. Jangan menulis path Windows di HTML.

## Langkah 7 — Susun landing page

Gabungkan navbar, hero dua kolom, dan grid card. Kelas yang wajib ada: `.navbar`, `.container`, `.hero`, `.course-grid`, `.card`. Di layar sempit (lebar di bawah 760px) hero dan grid menjadi satu kolom.

Judul daftar course pada versi akhir: **Program Belajar Unggulan**. Itu hasil eksperimen view: tampilan berubah, data controller tidak berubah.

## Langkah 8 — Buktikan tiga lapisan

1. Route: `php artisan route:list` → `home` menunjuk `HomeController@index`.
2. Controller: tambah course baru di `$featured`. Card bertambah tanpa menyalin HTML.
3. View: ubah judul hanya di Blade. Data course tetap.

Course yang sudah ditambahkan pada kode akhir:

- `UI-01` UI Web Dasar
- `JS-01` JavaScript Dasar

## Langkah 9 — Commit kedua

```bash
git add .
git commit -m "feat: add home route controller and Blade landing page"
git log --oneline -5
```

Jangan commit `.env`.

## Langkah 10 — README dan uji

README menjelaskan requirement dan cara menjalankan. Centang uji ini sebelum dikumpulkan:

| No | Skenario | Harapan |
| --- | --- | --- |
| 1 | Buka `/` | Landing tampil tanpa error |
| 2 | `php artisan route:list` | Route `home` terlihat |
| 3 | Ubah nama course di controller | Nama baru muncul setelah refresh |
| 4 | Tambah satu course | Jumlah card bertambah |
| 5 | Buka gambar hero | Tidak 404 |
| 6 | Lebar mobile | Hero dan grid satu kolom |
| 7 | Buka `/abc` | Laravel 404, bukan home |
| 8 | Ikuti README dari awal | Project jalan lagi |

Screenshot kelas disimpan sendiri di `evidence/week-07/` (`01-tools.png`, `02-laravel-home.png`, `03-route-list.png`, `04-mobile-view.png`, `05-git-log.png`). Diagram alur sudah disediakan: `evidence/week-07/request-lifecycle.svg`.

## Yang sengaja tidak dikerjakan

Database, login, CRUD, dan Ajax. Itu batas pertemuan 7. Lanjut ke zip pertemuan 9 hanya setelah langkah ini bisa dijelaskan: browser meminta `/`, route `home` memanggil `HomeController@index`, controller mengirim data, Blade menghasilkan HTML.
