<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelKandidat.php';
require_once __DIR__ . '/Kandidat.php';

/**
 * Model Kandidat: mengakses tabel `kandidat` di database.
 * Mewarisi DB (koneksi & executeQuery) dan memenuhi KontrakModelKandidat.
 */
class TabelKandidat extends DB implements KontrakModelKandidat {

    // Teruskan konfigurasi koneksi ke constructor DB
    public function __construct(
        string $host = "localhost",
        string $db_name = "mvp_kandidat_db",
        string $username = "root",
        string $password = ""
    ) {
        parent::__construct($host, $db_name, $username, $password);
    }

    // Ubah satu baris hasil query (array) menjadi objek Kandidat
    private function mapRowToKandidat(array $row): Kandidat {
        return new Kandidat(
            (int)$row['id'],
            $row['nama'],
            $row['posisi'],
            $row['domisili'],
            (int)$row['skorTes'],
            (int)$row['pengalamanTahun']
        );
    }

    public function getAllKandidat(): array {
        // TODO: Ambil seluruh data kandidat lalu mapping ke array objek Kandidat
        $this->executeQuery("SELECT * FROM kandidat ORDER BY id ASC");
        // Setiap baris diubah jadi objek Kandidat lewat mapRowToKandidat
        return array_map([$this, 'mapRowToKandidat'], $this->getAllResult());
    }

    public function getKandidatById(int $id): ?Kandidat {
        // TODO: Ambil 1 kandidat berdasarkan $id, kembalikan null jika tidak ada
        $stmt = $this->executeQuery("SELECT * FROM kandidat WHERE id = :id", [':id' => $id]);
        $row = $stmt->fetch();  // false jika data tidak ditemukan
        return $row ? $this->mapRowToKandidat($row) : null;
    }

    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        // TODO: Jalankan query INSERT INTO kandidat
        // id tidak diisi karena AUTO_INCREMENT
        $this->executeQuery(
            "INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun)
            VALUES (:nama, :posisi, :domisili, :skorTes, :pengalamanTahun)",
            [
                ':nama' => $nama,
                ':posisi' => $posisi,
                ':domisili' => $domisili,
                ':skorTes' => $skorTes,
                ':pengalamanTahun' => $pengalamanTahun
            ]
        );
    }

    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        // TODO: Jalankan query UPDATE kandidat WHERE id = :id
        $this->executeQuery(
            "UPDATE kandidat
            SET nama = :nama, posisi = :posisi, domisili = :domisili, skorTes = :skorTes, pengalamanTahun = :pengalamanTahun
            WHERE id = :id",
            [
                ':id' => $id,
                ':nama' => $nama,
                ':posisi' => $posisi,
                ':domisili' => $domisili,
                ':skorTes' => $skorTes,
                ':pengalamanTahun' => $pengalamanTahun
            ]
        );
    }

    public function deleteKandidat(int $id): void {
        // TODO: Jalankan query DELETE FROM kandidat WHERE id = :id
        $this->executeQuery("DELETE FROM kandidat WHERE id = :id", [':id' => $id]);
    }
}
?>

