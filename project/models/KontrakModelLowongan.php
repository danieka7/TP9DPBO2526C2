<?php
/**
 * Kontrak Model Lowongan.
 * Presenter hanya bergantung pada interface ini, bukan pada TabelLowongan langsung,
 * sehingga implementasi Model bisa diganti tanpa mengubah Presenter.
 */
interface KontrakModelLowongan {
    /**
     * Mengambil seluruh data lowongan sebagai array objek Lowongan
     * @return Lowongan[]
     */
    public function getAllLowongan(): array;

    // mengambil satu data lowongan berdasarkan ID
    public function getLowonganById(int $id): ?Lowongan;

    // Menyimpan lowongan baru (INSERT)
    public function addLowongan(string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void;
    // Mengubah data lowongan berdasarkan ID (UPDATE)
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void;
    // Menghapus lowongan berdasarkan ID (DELETE)
    public function deleteLowongan(int $id): void;

}
?>