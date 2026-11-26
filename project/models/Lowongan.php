<?php
/**
 * Entitas Lowongan: satu objek mewakili satu baris tabel `lowongan`.
 * Atribut private dan diakses lewat getter/setter (enkapsulasi).
 */
class Lowongan {
    private ?int $id;               // null jika belum disimpan ke database
    private string $judul;          // nama posisi, misal "Backend Developer"
    private string $departemen;
    private string $lokasi;
    private string $tipeKerja;      // Full-time / Kontrak / Internship
    private int $gaji;              // gaji per bulan dalam rupiah
    private int $kuota;             // jumlah orang yang dibutuhkan
    private string $status;         // Buka / Tutup

    public function __construct(
        ?int $id,
        string $judul,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $gaji,
        int $kuota,
        string $status
    ) {
        $this->id = $id;
        $this->judul = $judul;
        $this->departemen = $departemen;
        $this->lokasi = $lokasi;
        $this->tipeKerja = $tipeKerja;
        $this->gaji = $gaji;
        $this->kuota = $kuota;
        $this->status = $status;
    }

    // ---------- Getter ----------
    public function getId(): ?int {
        return  $this->id;
    }
    public function getJudul(): string {
        return $this->judul;
    }
    public function getDepartemen(): string {
        return $this->departemen;
    }
    public function getLokasi(): string {
        return $this->lokasi;
    }
    public function getTipeKerja(): string {
        return $this->tipeKerja;
    }
    public function getGaji(): int {
        return $this->gaji;
    }
    public function getKuota(): int {
        return $this->kuota;
    }
    public function getStatus(): string {
        return $this->status;
    }


    // ---------- Setter (id tidak punya setter karena dibuat otomatis oleh database) ----------
    public function setJudul(string $judul): void {
        $this->judul = $judul;
    }
    public function setDepartemen(string $departemen): void {
        $this->departemen = $departemen;
    }
    public function setLokasi(string $lokasi): void {
        $this->lokasi = $lokasi;
    }
    public function setTipeKerja(string $tipeKerja): void {
        $this->tipeKerja = $tipeKerja;
    }
    public function setGaji(int $gaji): void {
        $this->gaji = $gaji;
    }
    public function setKuota(int $kuota): void {
        $this->kuota = $kuota;
    }
    public function setStatus(string $status): void {
        $this->status = $status;
    }
}
?>