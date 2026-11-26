<?php
/**
 * Kelas dasar koneksi database (PDO).
 * Diwarisi oleh TabelKandidat dan TabelLowongan supaya
 * kode koneksi dan eksekusi query tidak ditulis berulang.
 */
class DB {
    // Konfigurasi koneksi (nilai default untuk XAMPP/Laragon)
    private string $host = "localhost";
    private string $db_name = "mvp_kandidat_db";
    private string $username = "root";
    private string $password = "";
    private ?PDO $conn = null;               // objek koneksi aktif
    private ?PDOStatement $result = null;    // hasil query terakhir

    // Simpan konfigurasi lalu langsung buka koneksi
    public function __construct(
        string $host = "localhost",
        string $db_name = "mvp_kandidat_db",
        string $username = "root",
        string $password = ""
    ) {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
        $this->conn = $this->connect();
    }

    // Membuat objek PDO ke MySQL
    public function connect(): PDO {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // error dilempar sebagai exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // hasil berupa array asosiatif
                PDO::ATTR_EMULATE_PREPARES => false,               // pakai prepared statement asli MySQL
            ];
            return new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new RuntimeException("Koneksi Database Gagal: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Menjalankan query dengan prepared statement.
     * $params berisi nilai placeholder, misal [':id' => 1], agar aman dari SQL injection.
     */
    public function executeQuery(string $query, array $params = []): PDOStatement {
        if ($this->conn === null) {
            throw new RuntimeException("Tidak ada koneksi database aktif.");
        }
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $this->result = $stmt;
            return $stmt;
        } catch (PDOException $e) {
            throw new RuntimeException("Query Gagal: " . $e->getMessage(), 0, $e);
        }
    }

    // Mengambil semua baris dari query terakhir
    public function getAllResult(): array {
        if ($this->result === null) {
            return [];
        }
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menutup koneksi
    public function close(): void {
        $this->result = null;
        $this->conn = null;
    }
}
?>
