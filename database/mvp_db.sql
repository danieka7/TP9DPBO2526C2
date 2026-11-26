-- Database: mvp_kandidat_db
-- Import file ini ke MySQL / phpMyAdmin sebelum menjalankan aplikasi.
CREATE DATABASE IF NOT EXISTS `mvp_kandidat_db`;
USE `mvp_kandidat_db`;

-- Tabel kandidat: data pelamar kerja
-- (kolom posisi berupa teks bebas, tidak berelasi ke tabel lowongan)
CREATE TABLE IF NOT EXISTS `kandidat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(100) NOT NULL,
  `domisili` varchar(100) NOT NULL,
  `skorTes` int(11) NOT NULL DEFAULT 0,          -- skala 0-100
  `pengalamanTahun` int(11) NOT NULL DEFAULT 0,  -- lama pengalaman dalam tahun
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal kandidat
INSERT INTO `kandidat` (`id`, `nama`, `posisi`, `domisili`, `skorTes`, `pengalamanTahun`) VALUES
(1, 'Raka Pratama', 'Backend Developer', 'Bandung', 92, 3),
(2, 'Sinta Maharani', 'UI/UX Designer', 'Jakarta', 88, 2),
(3, 'Dimas Nugroho', 'Data Analyst', 'Yogyakarta', 85, 1),
(4, 'Nadia Kusuma', 'Frontend Developer', 'Surabaya', 90, 2),
(5, 'Bagas Setiawan', 'QA Engineer', 'Bandung', 81, 1);


-- Tabel lowongan: posisi pekerjaan yang dibuka perusahaan
CREATE TABLE IF NOT EXISTS `lowongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,                 -- nama posisi
  `departemen` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `tipeKerja` varchar(50) NOT NULL,              -- Full-time / Kontrak / Internship
  `gaji` int(11) NOT NULL DEFAULT 0,             -- gaji per bulan (rupiah)
  `kuota` int(11) NOT NULL DEFAULT 1,            -- jumlah orang yang dibutuhkan
  `status` varchar(20) NOT NULL DEFAULT 'Buka',  -- Buka / Tutup
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal lowongan
INSERT INTO `lowongan` (`id`, `judul`, `departemen`, `lokasi`, `tipeKerja`, `gaji`, `kuota`, `status`) VALUES
(1, 'Backend Developer', 'IT', 'Bandung', 'Full-time', 9000000, 2, 'Buka'),
(2, 'Frontend Developer', 'IT', 'Jakarta', 'Full-time', 8500000, 2, 'Buka'),
(3, 'UI/UX Designer', 'Product', 'Remote', 'Kontrak', 7000000, 1, 'Buka'),
(4, 'Data Analyst', 'Data', 'Jakarta', 'Full-time', 10000000, 1, 'Tutup'),
(5, 'HR Generalist', 'HR', 'Surabaya', 'Full-time', 6500000, 1, 'Buka'),
(6, 'QA Engineer', 'IT', 'Bandung', 'Kontrak', 7500000, 2, 'Buka'),
(7, 'Digital Marketing', 'Marketing', 'Yogyakarta', 'Full-time', 6000000, 1, 'Tutup'),
(8, 'Software Engineer Intern', 'IT', 'Bandung', 'Internship', 3000000, 5, 'Buka');
