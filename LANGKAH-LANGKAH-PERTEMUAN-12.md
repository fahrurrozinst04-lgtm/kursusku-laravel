# KursusKu — Pertemuan 12
## Relasi Course, Participant, dan Enrollment

Project lama tidak dibuat ulang. File di paket ini ditempel ke project KursusKu yang sudah punya CRUD Course.

Yang tidak diubah:
- migration tabel courses
- CourseController dan form CRUD course
- $fillable dan $casts pada model Course

Yang ditambah:
- tabel participants dan enrollments
- foreign key cascadeOnDelete pada kedua relasi
- unique gabungan (participant_id, course_id)
- model Participant, model Enrollment, relasi di Course
- form enrollment, validasi duplikat, laporan relasional

---

## Penyesuaian penting terhadap PDF

PDF menulis status course `active`. Di project ini enum status course adalah:

draft | open | closed

Karena itu dropdown course memakai where('status', 'open'). Kalau ditulis active, dropdown kosong dan data dummy cascade gagal masuk ke database.

Saat uji cascade di Tinker, gunakan 'status' => 'open', bukan 'active'.

PDF uji hapus participant sempat menulis Course::find(...) === null harus true. Itu tidak sesuai kesimpulan PDF sendiri. Hasil yang benar: course tetap ada. Enrollment yang hilang. Participant yang dihapus.

---

## Struktur file paket ini

Salin setiap file ke folder yang sama di dalam project Laragon. Timpa file yang sudah ada hanya untuk file yang tercantum di bawah.

```
app/Models/course.php                          (ditambah relasi, isi lama tetap)
app/Models/Participant.php                     (baru)
app/Models/Enrollment.php                      (baru)
app/Http/Controllers/EnrollmentController.php  (baru)
database/migrations/2026_11_18_120000_create_participants_table.php
database/migrations/2026_11_18_120001_create_enrollments_table.php
database/seeders/ParticipantSeeder.php         (baru)
database/seeders/DatabaseSeeder.php            (ditambah panggilan ParticipantSeeder)
resources/views/enrollments/create.blade.php   (form dua dropdown)
resources/views/enrollments/index.blade.php    (laporan, baru)
resources/views/partials/navbar.blade.php      (menu Laporan)
routes/web.php                                 (resource enrollment)
```

Jangan salin file .env. Password database tetap di komputer sendiri.

---

## Langkah pasang (berurutan)

### 0. Checkpoint

Di folder project KursusKu:

```
php artisan --version
php artisan migrate:status
```

Pastikan migration create_courses_table sudah Ran dan halaman daftar kursus masih terbuka.

```
git add .
git commit -m "checkpoint before meeting 12 relations"
```

### 1. Tempel file

Salin isi paket ini ke project, lalu cek tiga file model bisa dibuka tanpa error sintaks.

### 2. Migrasi

```
php artisan migrate
php artisan migrate:status
```

Yang harus DONE / Ran:
- 2026_11_18_120000_create_participants_table
- 2026_11_18_120001_create_enrollments_table

### 3. Cek foreign key

Di phpMyAdmin atau HeidiSQL, buka tabel enrollments, bagian Structure / Foreign keys.

Harus ada:
- participant_id menuju participants.id, ON DELETE CASCADE
- course_id menuju courses.id, ON DELETE CASCADE
- index unik participant_id + course_id

SQL pembanding:

```
SHOW CREATE TABLE enrollments;
```

### 4. Data dummy participant

Kalau tabel courses masih kosong:

```
php artisan db:seed --class=CourseSeeder
```

Lalu:

```
php artisan db:seed --class=ParticipantSeeder
```

Isi seeder (domain example.test, bukan email sungguhan):

| Nama | Email | Phone |
| --- | --- | --- |
| Alya Rahma | alya@example.test | 081200000001 |
| Bima Putra | bima@example.test | 081200000002 |
| Citra Nabila | citra@example.test | kosong |

Cek:

```
php artisan tinker
>>> App\Models\Participant::all(['id','name','email']);
>>> App\Models\Course::where('status','open')->get(['id','code','name']);
```

### 5. Buka form

```
php artisan serve
```

Buka /enrollments/create.

Navbar: Pendaftaran dan Laporan.

### 6. Matriks uji (wajib)

| # | Skenario | Hasil |
| --- | --- | --- |
| 1 | Buka /enrollments/create | Participant dan course berstatus open tampil |
| 2 | Participant dikosongkan | Ditolak, pesan validasi |
| 3 | Course dikosongkan | Ditolak, pesan validasi |
| 4 | Alya ke Laravel Dasar | Tersimpan, status registered |
| 5 | Alya ke Laravel Dasar lagi | Ditolak: Participant ini sudah terdaftar pada course tersebut. |
| 6 | Alya ke PHP Fundamental | Boleh tersimpan |
| 7 | Bima ke Laravel Dasar | Boleh tersimpan |
| 8 | Buka /enrollments | Nama participant, nama course, start date course, status |

Di database:

```
SELECT id, participant_id, course_id, status
FROM enrollments
ORDER BY id DESC;
```

participant_id dan course_id harus sama dengan id di tabel master. Tidak ada kolom nama di enrollments.

### 7. Uji cascade — hapus course dummy

Jangan hapus kursus yang dipakai laporan. Buat data khusus.

```
php artisan tinker
```

```
use App\Models\Course;
use App\Models\Participant;
use App\Models\Enrollment;

$course = Course::create([
    'code' => 'TEST-CASCADE-COURSE',
    'name' => 'Course Cascade Test',
    'fee' => 10000,
    'quota' => 5,
    'start_date' => now()->addWeek()->toDateString(),
    'status' => 'open',
]);

$participant = Participant::create([
    'name' => 'Dummy Cascade Course',
    'email' => 'cascade-course@example.test',
]);

Enrollment::create([
    'participant_id' => $participant->id,
    'course_id' => $course->id,
    'status' => 'registered',
]);

Enrollment::where('course_id', $course->id)->count(); // 1
$course->delete();
Enrollment::where('course_id', $course->id)->count(); // 0
Participant::find($participant->id) !== null;         // true, participant tetap ada
```

### 8. Uji cascade — hapus participant dummy

```
$course2 = Course::create([
    'code' => 'TEST-CASCADE-PART',
    'name' => 'Participant Cascade Test',
    'fee' => 10000,
    'quota' => 5,
    'start_date' => now()->addDays(10)->toDateString(),
    'status' => 'open',
]);

$participant2 = Participant::create([
    'name' => 'Dummy Participant Delete',
    'email' => 'cascade.participant@example.test',
]);

Enrollment::create([
    'participant_id' => $participant2->id,
    'course_id' => $course2->id,
    'status' => 'registered',
]);

Enrollment::where('participant_id', $participant2->id)->count(); // 1
$participant2->delete();
Enrollment::where('participant_id', $participant2->id)->count(); // 0
Course::find($course2->id) !== null;                             // true, course tetap ada

$course2->delete();
$participant->delete();
```

### 9. Commit

```
git add .
git commit -m "feat: add participant course enrollment relations"
```

---

## Yang harus bisa dijelaskan (code defense)

1. Foreign key menjaga agar participant_id dan course_id hanya menunjuk baris yang benar-benar ada. Angka id biasa tidak melakukan itu.
2. Unique dipasang pada pasangan participant + course. Kalau masing-masing kolom di-unique, satu orang hanya bisa ikut satu kursus, atau satu kursus hanya bisa punya satu peserta.
3. start_date tidak disimpan di enrollments karena sudah ada di courses. Laporan mengambilnya lewat $enrollment->course->start_date.
4. Tanpa cascade, menghapus course atau participant gagal, atau enrollment menjadi data yatim.
5. belongsTo() ada di sisi banyak (Enrollment milik satu Course / satu Participant). hasMany() ada di sisi satu (Course punya banyak Enrollment).

Jawaban checkpoint ERD:
- Alya ikut 3 kursus berarti 3 baris enrollment.
- 20 peserta ikut Laravel Dasar berarti 20 baris enrollment untuk course itu.
- course_name tidak disimpan lagi karena nama diambil dari tabel courses lewat course_id.

---

## Jika error

| Gejala | Perbaikan |
| --- | --- |
| Cannot add foreign key | Pastikan tabel courses sudah ada, lalu migrate participants dulu, baru enrollments |
| Dropdown course kosong | Status course harus open, bukan active atau draft |
| Class Participant not found | use App\Models\Participant; dan file ada di app/Models/Participant.php |
| Duplicate entry tanpa pesan ramah | Pastikan pesan course_id.unique ada di store() |
| Attempt to read property name on null | Enrollment menunjuk id yang sudah terhapus; cek FK cascade |
| Hapus course gagal | SHOW CREATE TABLE enrollments harus memuat ON DELETE CASCADE |

```
php artisan route:list
php artisan migrate:status
php artisan optimize:clear
```
