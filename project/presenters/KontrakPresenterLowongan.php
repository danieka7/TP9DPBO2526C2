<?php
/**
 * Kontrak Presenter Lowongan.
 * Daftar aksi yang bisa dipanggil index.php untuk modul lowongan.
 */
interface KontrakPresenterLowongan {
    // Menghasilkan HTML halaman daftar lowongan
    public function tampilkanLowongan(): string;
    // Menghasilkan HTML form; $id null = form tambah, ada isinya = form ubah
    public function tampilkanFormLowongan(?int $id = null): string;
    // Aksi CRUD: simpan ke Model lalu redirect ke halaman daftar
    public function tambahLowongan(string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void;
    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void;
    public function hapusLowongan(int $id): void;
}
?>