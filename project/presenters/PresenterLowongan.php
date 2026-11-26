<?php
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

/**
 * Presenter Lowongan: penghubung Model dan View.
 * Semua logika tampilan (loop, hitung total, format rupiah, pilihan dropdown,
 * escape HTML) dikerjakan di sini, sehingga View tetap pasif.
 */
class PresenterLowongan implements KontrakPresenterLowongan {
    // Tipe properti memakai interface agar tidak terikat ke kelas konkret
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    // Pilihan tetap untuk dropdown Tipe Kerja dan Status di form
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
        // Teks di-escape dengan htmlspecialchars untuk mencegah XSS
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul());
        $departemen = htmlspecialchars($lowongan->getDepartemen());
        $lokasi = htmlspecialchars($lowongan->getLokasi());
        $tipeKerja = htmlspecialchars($lowongan->getTipeKerja());
        // Format rupiah: 9000000 -> "Rp 9.000.000"
        $gaji = 'Rp ' . number_format($lowongan->getGaji(), 0, ',', '.');
        $kuota = number_format($lowongan->getKuota()) . ' Orang';
        $status = htmlspecialchars($lowongan->getStatus());
        // Warna badge: hijau untuk Buka, merah untuk Tutup (class ada di skin_lowongan.html)
        $statusClass = $lowongan->getStatus() === 'Buka' ? 'badge-open' : 'badge-closed';

        // $no = nomor urut tampilan, $id = id asli untuk tombol Edit/Hapus
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

    // Kembali ke halaman daftar lowongan (dipakai setelah tambah/ubah/hapus)
    private function redirect(): void {
        header("Location: index.php?modul=lowongan");
        exit();
    }

    // Ambil semua lowongan, rakit baris tabel, lalu serahkan ke View
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

    // $id ada -> form ubah berisi data lama; $id null -> form tambah kosong
    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan !== null) {
                // Objek diubah jadi array biasa karena View tidak boleh mengenal entitas Lowongan
                $formData = [
                    'id' => $lowongan->getId(),
                    'judul' => $lowongan->getJudul(),
                    'departemen' => $lowongan->getDepartemen(),
                    'lokasi' => $lowongan->getLokasi(),
                    'gaji' => $lowongan->getGaji(),
                    'kuota' => $lowongan->getKuota(),
                    // Dropdown dengan nilai lama sudah terpilih
                    'tipeKerjaOptions' => $this->buildOptionsHtml(self::TIPE_KERJA, $lowongan->getTipeKerja()),
                    'statusOptions' => $this->buildOptionsHtml(self::STATUS, $lowongan->getStatus()),
                ];
                return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
            }
        }

        // Form tambah: dropdown default Full-time dan Buka
        $formData = [
            'tipeKerjaOptions' => $this->buildOptionsHtml(self::TIPE_KERJA, 'Full-time'),
            'statusOptions' => $this->buildOptionsHtml(self::STATUS, 'Buka'),
        ];
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", $formData);
    }

    // Aksi CRUD: teruskan ke Model, lalu redirect agar refresh tidak mengirim ulang form
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
