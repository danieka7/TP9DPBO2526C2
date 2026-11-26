<?php
require_once __DIR__ . '/KontrakViewLowongan.php';

/**
 * View Lowongan (Passive View).
 * Hanya membaca file template HTML lalu mengganti token {{...}}
 * dengan string yang sudah disiapkan Presenter.
 */
class ViewLowongan implements KontrakViewLowongan {
    private string $skinPath;   // template halaman daftar
    private string $formPath;   // template halaman form

    // Path template bisa diganti; jika kosong pakai file default di folder template/
    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin_lowongan.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form_lowongan.html';
    }

    public function renderDaftar(string $tableRowsHtml, int $totalData): string {
        $template = file_get_contents($this->skinPath);

        // Pasangan token => nilai pengganti
        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ];

        // Ganti semua token sekaligus
        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    public function renderForm(string $title, string $action, array $formData = []): string {
        $template = file_get_contents($this->formPath);

        // Opsi <select> sudah dirakit oleh Presenter, View hanya menempelkan.
        // Field teks di-escape; ?? memberi nilai default saat form tambah
        $tokens = [
            '{{FORM_TITLE}}'         => htmlspecialchars($title),
            '{{ACTION}}'             => htmlspecialchars($action),
            '{{ID}}'                 => htmlspecialchars((string)($formData['id'] ?? '')),
            '{{JUDUL}}'              => htmlspecialchars($formData['judul'] ?? ''),
            '{{DEPARTEMEN}}'         => htmlspecialchars($formData['departemen'] ?? ''),
            '{{LOKASI}}'             => htmlspecialchars($formData['lokasi'] ?? ''),
            '{{GAJI}}'               => htmlspecialchars((string)($formData['gaji'] ?? 0)),
            '{{KUOTA}}'              => htmlspecialchars((string)($formData['kuota'] ?? 1)),
            '{{TIPE_KERJA_OPTIONS}}' => $formData['tipeKerjaOptions'] ?? '',
            '{{STATUS_OPTIONS}}'     => $formData['statusOptions'] ?? '',
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
