<?php
/**
 * Entitas Kandidat: satu objek mewakili satu baris tabel `kandidat`.
 * Atribut private dan diakses lewat getter/setter (enkapsulasi).
 */
class Kandidat {
    private ?int $id;               // null jika belum disimpan ke database
    private string $nama;
    private string $posisi;         // posisi yang dilamar
    private string $domisili;
    private int $skorTes;           // 0 - 100
    private int $pengalamanTahun;

    public function __construct(
        ?int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->posisi = $posisi;
        $this->domisili = $domisili;
        $this->skorTes = $skorTes;
        $this->pengalamanTahun = $pengalamanTahun;
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getNama(): string { return $this->nama; }
    public function getPosisi(): string { return $this->posisi; }
    public function getDomisili(): string { return $this->domisili; }
    public function getSkorTes(): int { return $this->skorTes; }
    public function getPengalamanTahun(): int { return $this->pengalamanTahun; }

    // Setter (id tidak punya setter karena dibuat otomatis oleh database)
    public function setNama(string $nama): void { $this->nama = $nama; }
    public function setPosisi(string $posisi): void { $this->posisi = $posisi; }
    public function setDomisili(string $domisili): void { $this->domisili = $domisili; }
    public function setSkorTes(int $skorTes): void { $this->skorTes = $skorTes; }
    public function setPengalamanTahun(int $pengalamanTahun): void { $this->pengalamanTahun = $pengalamanTahun; }
}
?>
