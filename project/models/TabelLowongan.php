<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelLowongan.php';
require_once __DIR__ . '/Lowongan.php';

/**
 * Model Lowongan: mengakses tabel `lowongan` di database.
 * Mewarisi DB (koneksi & executeQuery) dan memenuhi KontrakModelLowongan.
 */
class TabelLowongan extends DB implements KontrakModelLowongan {
    // Teruskan konfigurasi koneksi ke constructor DB
    public function __construct(
        string $host = "localhost",
        string $db_name = "mvp_kandidat_db",
        string $username = "root",
        string $password = ""
    ){
        parent::__construct($host, $db_name, $username, $password);
    }

    // Ubah satu baris hasil query (array) menjadi objek Lowongan
    private function mapRowToLowongan(array $row): Lowongan {
        return new Lowongan(
            (int)$row['id'],
            $row['judul'],
            $row['departemen'],
            $row['lokasi'],
            $row['tipeKerja'],
            (int)$row['gaji'],
            (int)$row['kuota'],
            $row['status']
        );
    }

    // Ambil semua lowongan, urut berdasarkan id
    public function getAllLowongan(): array {
        $this->executeQuery("SELECT * FROM lowongan ORDER BY id ASC");
        return array_map([$this, 'mapRowToLowongan'], $this->getAllResult());
    }

    // Ambil satu lowongan; null jika id tidak ditemukan
    public function getLowonganById(int $id): ?Lowongan {
        $stmt = $this->executeQuery("SELECT * FROM lowongan WHERE id = :id", [':id' => $id]);
        $row = $stmt->fetch();  // false jika data tidak ditemukan
        return $row ? $this->mapRowToLowongan($row) : null;
    }

    // Simpan lowongan baru; id tidak diisi karena AUTO_INCREMENT
    public function addLowongan(string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void {
        $this->executeQuery(
            "INSERT INTO lowongan (judul, departemen, lokasi, tipeKerja, gaji, kuota, status)
            VALUES (:judul, :departemen, :lokasi, :tipeKerja, :gaji, :kuota, :status)",
            [
                ':judul' => $judul,
                ':departemen' => $departemen,
                ':lokasi' => $lokasi,
                ':tipeKerja' => $tipeKerja,
                ':gaji' => $gaji,
                ':kuota' => $kuota,
                ':status' => $status
            ]
        );
    }

    // Ubah seluruh kolom lowongan dengan id tertentu
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void {
        $this->executeQuery(
            "UPDATE lowongan
            SET judul = :judul, departemen = :departemen, lokasi = :lokasi, tipeKerja = :tipeKerja, gaji = :gaji, kuota = :kuota, status = :status
            WHERE id = :id",
            [
                ':id' => $id,
                ':judul' => $judul,
                ':departemen' => $departemen,
                ':lokasi' => $lokasi,
                ':tipeKerja' => $tipeKerja,
                ':gaji' => $gaji,
                ':kuota' => $kuota,
                ':status' => $status
            ]
        );
    }

    // Hapus lowongan berdasarkan id
    public function deleteLowongan(int $id): void {
        $this->executeQuery("DELETE FROM lowongan WHERE id = :id", [':id' => $id]);
    }
}
?>