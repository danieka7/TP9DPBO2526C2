<?php
/**
 * Kontrak Model Kandidat.
 * Presenter hanya bergantung pada interface ini, bukan pada TabelKandidat langsung,
 * sehingga implementasi Model bisa diganti tanpa mengubah Presenter.
 */
interface KontrakModelKandidat {
    /**
     * Mengambil seluruh data kandidat sebagai array objek Kandidat.
     * @return Kandidat[]
     */
    public function getAllKandidat(): array;

    /**
     * Mengambil satu data kandidat berdasarkan ID.
     */
    public function getKandidatById(int $id): ?Kandidat;

    // Menyimpan kandidat baru (INSERT)
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    // Mengubah data kandidat berdasarkan ID (UPDATE)
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    // Menghapus kandidat berdasarkan ID (DELETE)
    public function deleteKandidat(int $id): void;
}
?>