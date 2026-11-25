<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';
require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

// Pilih modul berdasarkan ?modul=..., default: kandidat
$modul = $_GET['modul'] ?? 'kandidat';

if ($modul === 'lowongan') {
    $presenter = new PresenterLowongan(
        new TabelLowongan('localhost', 'mvp_kandidat_db', 'root', ''),
        new ViewLowongan()
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        $judul = trim($_POST['judul'] ?? '');
        $departemen = trim($_POST['departemen'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $tipeKerja = trim($_POST['tipeKerja'] ?? '');
        $gaji = (int)($_POST['gaji'] ?? 0);
        $kuota = (int)($_POST['kuota'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($action === 'tambah') {
            $presenter->tambahLowongan($judul, $departemen, $lokasi, $tipeKerja, $gaji, $kuota, $status);
        } elseif ($action === 'ubah') {
            $presenter->ubahLowongan($id, $judul, $departemen, $lokasi, $tipeKerja, $gaji, $kuota, $status);
        } elseif ($action === 'hapus') {
            $presenter->hapusLowongan($id);
        }

        header("Location: index.php?modul=lowongan");
        exit();
    } else {
        $screen = $_GET['screen'] ?? 'list';
        if ($screen === 'add') {
            echo $presenter->tampilkanFormLowongan();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormLowongan((int)$_GET['id']);
        } else {
            echo $presenter->tampilkanLowongan();
        }
    }
    exit();
}

$model = new TabelKandidat('localhost', 'mvp_kandidat_db', 'root', '');
$view = new ViewKandidat();
$presenter = new PresenterKandidat($model, $view);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Tangani $_POST['action'] untuk 'tambah', 'ubah', dan 'hapus'
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $posisi = trim($_POST['posisi'] ?? '');
    $domisili = trim($_POST['domisili'] ?? '');
    $skorTes = (int)($_POST['skorTes'] ?? 0);
    $pengalamanTahun = (int)($_POST['pengalamanTahun'] ?? 0);

    if ($action === 'tambah') {
        $presenter->tambahKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
    } elseif ($action === 'ubah') {
        $presenter->ubahKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
    } elseif ($action === 'hapus') {
        $presenter->hapusKandidat($id);
    }

    header("Location: index.php");
    exit();
} else {
    $screen = $_GET['screen'] ?? 'list';
    if ($screen === 'add') {
        echo $presenter->tampilkanFormKandidat();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormKandidat((int)$_GET['id']);
    } else {
        echo $presenter->tampilkanKandidat();
    }
}
?>
