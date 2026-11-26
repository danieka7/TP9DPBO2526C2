<?php
/**
 * Kontrak View (Strict Passive View)
 * View tidak mengenal entitas domain, tidak melakukan loop, dan tidak menghitung agregat.
 */
interface KontrakViewKandidat {
    // Menempelkan baris tabel (sudah jadi HTML) dan total data ke template daftar
    public function renderDaftar(string $tableRowsHtml, int $totalData): string;
    // Menempelkan judul, jenis aksi (tambah/ubah), dan isi field ke template form
    public function renderForm(string $title, string $action, array $formData = []): string;
}
?>
