# Test matrix Pertemuan 11 — Course CRUD lengkap

Catatan ini mengikuti aturan di Form Request, dengan contoh input yang berbeda dari tabel panduan (kode latihan memakai WEB-101 / PHP-101, bukan WEB-001).

| No | Skenario | Input / tindakan | Hasil yang diharapkan | Hasil aktual | Status |
| --- | --- | --- | --- | --- | --- |
| 1 | Kode duplikat saat create | `code` = `WEB-101` (sudah ada), field lain valid | Ditolak, error di bawah field kode | Pesan "Kode kursus sudah dipakai kursus lain." | PASS |
| 2 | Nama kosong | `name` = `""` | Ditolak, name required | Pesan "Nama kursus wajib diisi." | PASS |
| 3 | Nama terlalu pendek | `name` = `AI` | Ditolak, minimal 3 karakter | Pesan "Nama kursus minimal 3 karakter." | PASS |
| 4 | Biaya negatif | `fee` = `-500` | Ditolak, minimal 0 | Pesan "Biaya tidak boleh negatif." | PASS |
| 5 | Biaya bukan integer | `fee` = `12.5` | Ditolak, harus integer | Pesan "Biaya harus berupa bilangan bulat." | PASS |
| 6 | Kuota nol | `quota` = `0` | Ditolak | Pesan "Kuota harus antara 1 dan 500." | PASS |
| 7 | Kuota di atas batas | `quota` = `501` | Ditolak | Pesan "Kuota harus antara 1 dan 500." | PASS |
| 8 | Batas kuota minimum | `quota` = `1`, kode baru `QTA-001` | Diterima, kembali ke daftar | Flash "Kursus berhasil ditambahkan." | PASS |
| 9 | Batas kuota maksimum | `quota` = `500`, kode baru `QTA-500` | Diterima | Baris baru tampil dengan kuota 500 | PASS |
| 10 | Tanggal tidak valid | `start_date` = `2026-02-31` | Ditolak oleh rule date | Pesan "Tanggal mulai tidak valid." | PASS |
| 11 | Status di luar enum | `status` = `active` (diubah lewat DevTools) | Ditolak | Pesan "Status hanya boleh draft, open, atau closed." | PASS |
| 12 | Update memakai kode sendiri | Edit `WEB-101`, kode tidak diubah, nama jadi "Web Dasar Lanjutan" | Berhasil | Redirect ke detail + flash "Kursus berhasil diperbarui." | PASS |
| 13 | Update ke kode kursus lain | Edit `PHP-101`, `code` diganti `WEB-101` | Ditolak unique | Pesan kode milik kursus lain | PASS |
| 14 | Update field lain | Ubah fee, kuota, dan status `LRV-101` dengan nilai valid | Berhasil | Halaman detail menampilkan angka dan status baru | PASS |
| 15 | Hapus dummy | Buat `DEL-11`, klik Hapus, Cancel dulu, lalu OK | Cancel: data tetap. OK: hilang dari index | Flash "Kursus berhasil dihapus." | PASS |

Urutan uji: data valid → detail → edit tanpa ganti kode → error satu per satu → batas kuota → status invalid → hapus dummy paling akhir.
