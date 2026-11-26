<?php
require_once __DIR__ . '/KontrakViewKandidat.php';

/**
 * View Kandidat (Passive View).
 * Hanya membaca file template HTML lalu mengganti token {{...}}
 * dengan string yang sudah disiapkan Presenter.
 */
class ViewKandidat implements KontrakViewKandidat {
    private string $skinPath;   // template halaman daftar
    private string $formPath;   // template halaman form

    // Path template bisa diganti; jika kosong pakai file default di folder template/
    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form.html';
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

        // Nilai di-escape karena dimasukkan ke atribut value="..."
        // ?? memberi nilai default saat form tambah (formData kosong)
        $tokens = [
            '{{FORM_TITLE}}'       => htmlspecialchars($title),
            '{{ACTION}}'           => htmlspecialchars($action),
            '{{ID}}'               => htmlspecialchars((string)($formData['id'] ?? '')),
            '{{NAMA}}'             => htmlspecialchars($formData['nama'] ?? ''),
            '{{POSISI}}'           => htmlspecialchars($formData['posisi'] ?? ''),
            '{{DOMISILI}}'         => htmlspecialchars($formData['domisili'] ?? ''),
            '{{SKOR_TES}}'         => htmlspecialchars((string)($formData['skorTes'] ?? 0)),
            '{{PENGALAMAN_TAHUN}}' => htmlspecialchars((string)($formData['pengalamanTahun'] ?? 0)),
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
