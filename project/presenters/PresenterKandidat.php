<?php
require_once __DIR__ . '/KontrakPresenter.php';
require_once __DIR__ . '/../models/KontrakModelKandidat.php';
require_once __DIR__ . '/../views/KontrakView.php';

class PresenterKandidat implements KontrakPresenter {
    private KontrakModelKandidat $model;
    private KontrakView $view;

    public function __construct(KontrakModelKandidat $model, KontrakView $view) {
        // TODO: Simpan $model dan $view ke properti kelas
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Helper untuk merakit 1 baris HTML tabel kandidat.
     * Panggil dari dalam tampilkanKandidat() saat melakukan foreach.
     */
    private function buildRowHtml(int $no, Kandidat $kandidat): string {
        $id = (int)$kandidat->getId();
        $nama = htmlspecialchars($kandidat->getNama());
        $posisi = htmlspecialchars($kandidat->getPosisi());
        $domisili = htmlspecialchars($kandidat->getDomisili());
        $skorTes = number_format($kandidat->getSkorTes()) . ' / 100';
        $pengalaman = number_format($kandidat->getPengalamanTahun()) . ' Tahun';

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$nama}</strong></td>
            <td>{$posisi}</td>
            <td>{$domisili}</td>
            <td>{$skorTes}</td>
            <td>{$pengalaman}</td>
            <td class='col-actions'>
                <a href='index.php?screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php' style='display:inline;' onsubmit=\"return confirm('Hapus kandidat ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    public function tampilkanKandidat(): string {
        // TODO:
        // 1. Ambil data dari $this->model->getAllKandidat()
        // 2. Hitung $totalKandidat = count($listKandidat)
        // 3. Rakit $rowsHtml menggunakan loop + $this->buildRowHtml($no, $kandidat)
        // 4. Kembalikan $this->view->renderDaftar($rowsHtml, $totalKandidat)
        $listKandidat = $this->model->getAllKandidat();
        $totalKandidat = count($listKandidat);

        $rowsHtml = '';
        $no = 1;
        foreach ($listKandidat as $kandidat) {
            $rowsHtml .= $this->buildRowHtml($no++, $kandidat);
        }

        return $this->view->renderDaftar($rowsHtml, $totalKandidat);
    }

    public function tampilkanFormKandidat(?int $id = null): string {
        // TODO:
        // Jika $id !== null, ambil data via $this->model->getKandidatById($id) lalu panggil renderForm("Ubah Data Kandidat", "ubah", $formData)
        // Jika $id === null, panggil renderForm("Tambah Kandidat Baru", "tambah", [])
        if ($id !== null) {
            $kandidat = $this->model->getKandidatById($id);
            if ($kandidat !== null) {
                $formData = [
                    'id' => $kandidat->getId(),
                    'nama' => $kandidat->getNama(),
                    'posisi' => $kandidat->getPosisi(),
                    'domisili' => $kandidat->getDomisili(),
                    'skorTes' => $kandidat->getSkorTes(),
                    'pengalamanTahun' => $kandidat->getPengalamanTahun(),
                ];
                return $this->view->renderForm("Ubah Data Kandidat", "ubah", $formData);
            }
        }

        return $this->view->renderForm("Tambah Kandidat Baru", "tambah", []);
    }

    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        // TODO: Panggil $this->model->addKandidat(...) lalu redirect ke index.php
        $this->model->addKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        header("Location: index.php");
        exit();
    }

    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        // TODO: Panggil $this->model->updateKandidat(...) lalu redirect ke index.php
        $this->model->updateKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        header("Location: index.php");
        exit();
    }

    public function hapusKandidat(int $id): void {
        // TODO: Panggil $this->model->deleteKandidat($id) lalu redirect ke index.php
        $this->model->deleteKandidat($id);
        header("Location: index.php");
        exit();
    }
}
?>

