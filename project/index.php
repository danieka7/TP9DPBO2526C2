<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';

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

