# Tugas Praktikum 9 - Desain Pemrograman Berorientasi Objek

Aplikasi web PHP untuk mengelola data **kandidat** dan **lowongan pekerjaan**, dibangun dengan pola **Model–View–Presenter (MVP)** edisi **Strict Passive View**. Kedua modul memiliki fitur CRUD lengkap: tambah, lihat daftar, ubah (form terisi data lama), dan hapus.

## Desain Program

### Struktur Folder

```
project/
├── index.php                      # titik masuk: routing modul, screen, dan action POST
├── mvp_db.sql                     # skema + data awal database mvp_kandidat_db
├── models/
│   ├── DB.php                     # kelas dasar koneksi PDO + executeQuery()
│   ├── Kandidat.php               # entity Kandidat
│   ├── Lowongan.php               # entity Lowongan
│   ├── KontrakModelKandidat.php   # interface Model Kandidat
│   ├── KontrakModelLowongan.php   # interface Model Lowongan
│   ├── TabelKandidat.php          # implementasi Model Kandidat (query tabel kandidat)
│   └── TabelLowongan.php          # implementasi Model Lowongan (query tabel lowongan)
├── presenters/
│   ├── KontrakPresenterKandidat.php
│   ├── KontrakPresenterLowongan.php
│   ├── PresenterKandidat.php
│   └── PresenterLowongan.php
├── views/
│   ├── KontrakViewKandidat.php
│   ├── KontrakViewLowongan.php
│   ├── ViewKandidat.php
│   └── ViewLowongan.php
└── template/
    ├── skin.html / form.html                   # template modul Kandidat
    └── skin_lowongan.html / form_lowongan.html # template modul Lowongan
```

### Database

Database `mvp_kandidat_db` berisi dua tabel yang berdiri sendiri (tidak ada foreign key).

| Tabel | Kolom |
|---|---|
| `kandidat` | `id` (PK, auto increment), `nama`, `posisi`, `domisili`, `skorTes` (0–100), `pengalamanTahun` |
| `lowongan` | `id` (PK, auto increment), `judul`, `departemen`, `lokasi`, `tipeKerja` (Full-time / Kontrak / Internship), `gaji`, `kuota`, `status` (Buka / Tutup) |

### Peran Tiap Lapisan

| Lapisan | Kelas | Tanggung jawab |
|---|---|---|
| **Model** | `DB`, `TabelKandidat`, `TabelLowongan`, entity `Kandidat` & `Lowongan` | Akses database dengan prepared statement, lalu mengubah tiap baris hasil query menjadi objek entity. |
| **Presenter** | `PresenterKandidat`, `PresenterLowongan` | Mengambil data dari Model, menjalankan semua logika tampilan (loop baris tabel, hitung total, format angka/rupiah, merakit `<option>`, escape HTML), lalu menyerahkan string jadi ke View. Juga meneruskan aksi tambah/ubah/hapus ke Model. |
| **View** | `ViewKandidat`, `ViewLowongan` | Hanya membaca file template HTML dan mengganti token `{{...}}` dengan string dari Presenter. |

### Kontrak (Interface)

Setiap lapisan berkomunikasi melalui interface, bukan kelas konkret:

- `KontrakModelKandidat` / `KontrakModelLowongan`: `getAll…()`, `get…ById()`, `add…()`, `update…()`, `delete…()`
- `KontrakPresenterKandidat` / `KontrakPresenterLowongan`: `tampilkan…()`, `tampilkanForm…()`, `tambah…()`, `ubah…()`, `hapus…()`
- `KontrakViewKandidat` / `KontrakViewLowongan`: `renderDaftar($tableRowsHtml, $totalData)`, `renderForm($title, $action, $formData)`

Properti dan constructor Presenter bertipe interface (`KontrakModel…`, `KontrakView…`). Kelas konkret hanya dibuat di `index.php`, lalu disuntikkan ke Presenter.

`TabelKandidat` mewarisi `DB` dan mengimplementasikan `KontrakModelKandidat`. `PresenterKandidat` mengimplementasikan `KontrakPresenterKandidat` dan memakai Model serta View lewat kontraknya. `ViewKandidat` mengimplementasikan `KontrakViewKandidat`. Modul Lowongan memiliki struktur yang sama persis (`TabelLowongan`, `PresenterLowongan`, `ViewLowongan`, entity `Lowongan`).

### Penerapan Strict Passive View

- Folder `views/` tidak me-`require` Model maupun entity. Yang di-`require` hanya kontrak View-nya sendiri.
- Tidak ada `foreach` dan `count()` di `views/`. Baris tabel dirakit di `Presenter::buildRowHtml()`, opsi dropdown di `PresenterLowongan::buildOptionsHtml()`, dan total data dihitung di Presenter.
- Saat mengisi form ubah, Presenter mengubah objek entity menjadi array biasa sebelum dikirim, sehingga View tidak pernah menerima objek `Kandidat` atau `Lowongan`.

## Alur Program

### Routing di `index.php`

Semua request masuk lewat `index.php`, yang menentukan apa yang dijalankan dari tiga parameter:

| Parameter | Nilai | Keterangan |
|---|---|---|
| `?modul=` (GET) | `lowongan`, atau kosong untuk kandidat | Memilih modul yang dirakit (Model + View + Presenter) |
| `?screen=` (GET) | `list` (default), `add`, `edit&id=…` | Memilih halaman yang ditampilkan |
| `action` (POST) | `tambah`, `ubah`, `hapus` | Dikirim dari input hidden di form atau tombol Hapus |

### 1. Menampilkan Daftar (Read)

1. Browser membuka `index.php` (modul Kandidat) atau `index.php?modul=lowongan`.
2. `index.php` merakit Model, View, dan Presenter, lalu memanggil `tampilkanKandidat()` / `tampilkanLowongan()`.
3. Presenter memanggil `getAll…()` di Model. Model menjalankan `SELECT` dan mengembalikan array objek entity.
4. Presenter melakukan loop dengan `buildRowHtml()` untuk merakit baris tabel, dan menghitung total data dengan `count()`.
5. Presenter memanggil `renderDaftar($rowsHtml, $total)` di View. View mengganti token `{{TABLE_ROWS}}` dan `{{TOTAL_DATA}}` di template daftar.
6. HTML yang sudah jadi dikembalikan ke `index.php`, lalu ditampilkan dengan `echo`.

### 2. Membuka Form Tambah / Ubah

1. Pengguna menekan **+ Tambah** (`?screen=add`) atau **Edit** (`?screen=edit&id=3`).
2. `index.php` memanggil `tampilkanForm…()`, tanpa id untuk tambah dan dengan id untuk ubah.
3. Untuk **ubah**, Presenter mengambil data lewat `get…ById($id)` lalu mengubahnya menjadi array `$formData`. Untuk modul Lowongan, Presenter juga merakit `<option>` dengan nilai lama sudah terpilih.
4. View menempelkan judul, `action` (`tambah`/`ubah`), dan isi field ke template form. Untuk tambah, field kosong atau berisi nilai default.

### 3. Menyimpan Data (Create / Update)

1. Form dikirim dengan `POST` ke `index.php` (atau `index.php?modul=lowongan`).
2. `index.php` membaca `$_POST`, melakukan `trim()` pada teks dan cast `(int)` pada angka.
3. Berdasarkan `action`, `index.php` memanggil `tambah…()` atau `ubah…()` di Presenter.
4. Presenter memanggil `add…()` / `update…()` di Model, yang menjalankan `INSERT` / `UPDATE` dengan prepared statement.
5. Presenter melakukan redirect ke halaman daftar (pola *Post/Redirect/Get*), supaya refresh halaman tidak mengirim ulang form.

### 4. Menghapus Data (Delete)

1. Tombol **Hapus** di tiap baris adalah form kecil berisi `action=hapus` dan `id`, dengan konfirmasi `confirm()` di browser.
2. `index.php` memanggil `hapus…($id)`, lalu Presenter memanggil `delete…($id)` di Model (`DELETE … WHERE id = :id`).
3. Presenter melakukan redirect kembali ke halaman daftar.z