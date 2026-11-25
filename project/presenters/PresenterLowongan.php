<?php
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

class PresenterLowongan implements KontrakPresenterLowongan {
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    private const TIPE_KERJA = ['Full-time', 'Kontrak', 'Internship'];
    private const STATUS = ['Buka', 'Tutup'];

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view) {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Helper untuk merakit 1 baris HTML tabel lowongan.
     */
    private function buildRowHtml(int $no, Lowongan $lowongan): string {
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul());
        $departemen = htmlspecialchars($lowongan->getDepartemen());
        $lokasi = htmlspecialchars($lowongan->getLokasi());
        $tipeKerja = htmlspecialchars($lowongan->getTipeKerja());
        $gaji = 'Rp ' . number_format($lowongan->getGaji(), 0, ',', '.');
        $kuota = number_format($lowongan->getKuota()) . ' Orang';
        $status = htmlspecialchars($lowongan->getStatus());
        $statusClass = $lowongan->getStatus() === 'Buka' ? 'badge-open' : 'badge-closed';

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$judul}</strong></td>
            <td>{$departemen}</td>
            <td>{$lokasi}</td>
            <td>{$tipeKerja}</td>
            <td>{$gaji}</td>
            <td>{$kuota}</td>
            <td><span class='badge {$statusClass}'>{$status}</span></td>
            <td class='col-actions'>
                <a href='index.php?modul=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php?modul=lowongan' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    /**
     * Helper untuk merakit <option> pada <select>, dengan opsi terpilih ditandai.
     * Loop dilakukan di Presenter agar View tetap pasif.
     */
    private function buildOptionsHtml(array $options, string $selected): string {
        $html = '';
        foreach ($options as $option) {
            $value = htmlspecialchars($option);
            $attr = $option === $selected ? ' selected' : '';
            $html .= "<option value='{$value}'{$attr}>{$value}</option>";
        }
        return $html;
    }

    private function redirect(): void {
        header("Location: index.php?modul=lowongan");
        exit();
    }

    public function tampilkanLowongan(): string {
        $listLowongan = $this->model->getAllLowongan();
        $totalLowongan = count($listLowongan);

        $rowsHtml = '';
        $no = 1;
        foreach ($listLowongan as $lowongan) {
            $rowsHtml .= $this->buildRowHtml($no++, $lowongan);
        }

        return $this->view->renderDaftar($rowsHtml, $totalLowongan);
    }

    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan !== null) {
                $formData = [
                    'id' => $lowongan->getId(),
                    'judul' => $lowongan->getJudul(),
                    'departemen' => $lowongan->getDepartemen(),
                    'lokasi' => $lowongan->getLokasi(),
                    'gaji' => $lowongan->getGaji(),
                    'kuota' => $lowongan->getKuota(),
                    'tipeKerjaOptions' => $this->buildOptionsHtml(self::TIPE_KERJA, $lowongan->getTipeKerja()),
                    'statusOptions' => $this->buildOptionsHtml(self::STATUS, $lowongan->getStatus()),
                ];
                return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
            }
        }

        $formData = [
            'tipeKerjaOptions' => $this->buildOptionsHtml(self::TIPE_KERJA, 'Full-time'),
            'statusOptions' => $this->buildOptionsHtml(self::STATUS, 'Buka'),
        ];
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", $formData);
    }

    public function tambahLowongan(string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void {
        $this->model->addLowongan($judul, $departemen, $lokasi, $tipeKerja, $gaji, $kuota, $status);
        $this->redirect();
    }

    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, string $tipeKerja, int $gaji, int $kuota, string $status): void {
        $this->model->updateLowongan($id, $judul, $departemen, $lokasi, $tipeKerja, $gaji, $kuota, $status);
        $this->redirect();
    }

    public function hapusLowongan(int $id): void {
        $this->model->deleteLowongan($id);
        $this->redirect();
    }
}
?>
