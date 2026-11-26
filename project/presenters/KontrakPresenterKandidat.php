<?php
/**
 * Kontrak Presenter Kandidat.
 * Daftar aksi yang bisa dipanggil index.php untuk modul kandidat.
 */
interface KontrakPresenterKandidat {
    // Menghasilkan HTML halaman daftar kandidat
    public function tampilkanKandidat(): string;
    // Menghasilkan HTML form; $id null = form tambah, ada isinya = form ubah
    public function tampilkanFormKandidat(?int $id = null): string;
    // Aksi CRUD: simpan ke Model lalu redirect ke halaman daftar
    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    public function hapusKandidat(int $id): void;
}
?>
