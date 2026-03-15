<?php

namespace App\Exports;

use App\Models\PenerimaFinal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PenerimaFinalExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithCustomStartCell,
    WithEvents
{
    protected int $rowCount = 0;

    // ── Collection ────────────────────────────────────────────────────────────
    public function collection()
    {
        $items = PenerimaFinal::query()
            ->where('status_verifikasi', 'disetujui')
            ->orderByDesc('tanggal_penetapan')
            ->get();

        $this->rowCount = $items->count();

        return $items->map(function ($item, $index) {
            $prob = $item->probability ?? 0;

            if ($prob <= 1) {
                $prob = $prob * 100;
            }

            $nama = $item->nama_lengkap ?? $item->nama ?? '-';

            // Penghasilan: coba dari relasi calonPenerima, fallback ke kolom langsung.
            // Jika null atau 0, tampilkan teks supaya cell tidak kosong.
            $penghasilanRaw = $item->calonPenerima?->penghasilan
                ?? $item->penghasilan
                ?? null;

            $penghasilan = ($penghasilanRaw !== null && (int) $penghasilanRaw > 0)
                ? (int) $penghasilanRaw
                : 'Tidak Berpenghasilan';

            return [
                'No'              => $index + 1,
                'Nama Lengkap'    => $nama,
                'NIK'             => "\t" . ($item->nik ?? '-'),
                'No KK'           => "\t" . ($item->no_kk ?? '-'),
                'Nomor RT'        => $item->nomor_rt ?? '-',
                'Dusun'           => $item->nama_dusun ?? '-',
                'Pekerjaan'       => $item->pekerjaan ?? '-',
                'Penghasilan'     => $penghasilan,
                'Tanggungan'      => $item->jumlah_tanggungan ?? 0,
                'Aset'            => $item->aset_kepemilikan ?? '-',
                'Bantuan Lain'    => ucfirst($item->bantuan_lain ?? 'tidak'),
                'Usia'            => $item->usia ?? 0,
                'Probabilitas'    => round($prob, 1),
                'Status'          => ucfirst($item->status_verifikasi ?? '-'),
                'Tgl Penetapan'   => optional($item->tanggal_penetapan)->format('d-m-Y') ?? '-',
                'Periode Bantuan' => $item->periode_bantuan ?? '-',
                'Jumlah Bantuan'  => $item->jumlah_bantuan ?? 300000,
            ];
        });
    }

    // ── Headings ──────────────────────────────────────────────────────────────
    public function headings(): array
    {
        return [
            'No', 'Nama Lengkap', 'NIK', 'No KK', 'Nomor RT', 'Dusun',
            'Pekerjaan', 'Penghasilan (Rp)', 'Tanggungan',
            'Aset Kepemilikan', 'Bantuan Lain', 'Usia',
            'Probabilitas (%)', 'Status',
            'Tgl Penetapan', 'Periode Bantuan', 'Jumlah Bantuan (Rp)',
        ];
    }

    public function startCell(): string { return 'A5'; }
    public function title(): string     { return 'Penerima BLT-DD'; }

    // ── Lebar kolom ──────────────────────────────────────────────────────────
    public function columnWidths(): array
    {
        return [
            'A' => 5,  'B' => 28, 'C' => 20, 'D' => 20,
            'E' => 8,  'F' => 18, 'G' => 20, 'H' => 18,
            'I' => 9,  'J' => 22, 'K' => 13, 'L' => 7,
            'M' => 14, 'N' => 16, 'O' => 15, 'P' => 18, 'Q' => 20,
        ];
    }

    // ── Style header tabel (baris 5) ─────────────────────────────────────────
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A5:Q5')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 9,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A8A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        return [];
    }

    // ── Events ────────────────────────────────────────────────────────────────
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = 5 + $this->rowCount;

                $this->buildTitleBlock($sheet);
                $this->formatDataRows($sheet, $lastRow);
                $this->applyTableBorders($sheet, $lastRow);
                $this->buildSummaryRow($sheet, $lastRow);
                $this->buildFootnote($sheet, $lastRow);
                $this->applyPrintSettings($sheet);

                $sheet->freezePane('A6');
                $sheet->setAutoFilter("A5:Q5");
            },
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function buildTitleBlock(Worksheet $sheet): void
    {
        foreach (['A1:Q1', 'A2:Q2', 'A3:Q3', 'A4:Q4'] as $range) {
            $sheet->mergeCells($range);
        }

        $sheet->setCellValue('A1', 'LAPORAN PENERIMA BANTUAN LANGSUNG TUNAI DANA DESA (BLT-DD)');
        $sheet->setCellValue('A2', 'Desa Ngerong, Kecamatan Gempol, Kabupaten Pasuruan — Jawa Timur');
        $sheet->setCellValue('A3', 'Status: Disetujui  |  Dicetak: ' . now()->translatedFormat('d F Y, H:i') . ' WIB');
        $sheet->setCellValue('A4', '');

        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'name' => 'Arial', 'color' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'name' => 'Arial', 'color' => ['argb' => 'FF334155']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 9, 'italic' => true, 'name' => 'Arial', 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle('A3:Q3')->applyFromArray([
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E3A8A']],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(14);
        $sheet->getRowDimension(4)->setRowHeight(6);
        $sheet->getRowDimension(5)->setRowHeight(30);
    }

    private function formatDataRows(Worksheet $sheet, int $lastRow): void
    {
        for ($row = 6; $row <= $lastRow; $row++) {
            $isEven = ($row % 2 === 0);

            $sheet->getStyle("A{$row}:Q{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $isEven ? 'FFF8FAFC' : 'FFFFFFFF'],
                ],
                'font'      => ['name' => 'Arial', 'size' => 9],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);

            $sheet->getRowDimension($row)->setRowHeight(18);

            foreach (['A', 'E', 'I', 'L', 'M', 'N', 'O'] as $col) {
                $sheet->getStyle("{$col}{$row}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            foreach (['H', 'Q'] as $col) {
                $sheet->getStyle("{$col}{$row}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }

            // Format Penghasilan — angka pakai Rp, teks (Tidak Berpenghasilan) pakai italic gray
            $hVal = $sheet->getCell("H{$row}")->getValue();
            if (is_numeric($hVal)) {
                $sheet->getStyle("H{$row}")->applyFromArray([
                    'numberFormat' => ['formatCode' => '"Rp "#,##0'],
                    'alignment'    => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            } else {
                $sheet->getStyle("H{$row}")->applyFromArray([
                    'font'      => ['italic' => true, 'color' => ['argb' => 'FF94A3B8'], 'size' => 8, 'name' => 'Arial'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            }
            $sheet->getStyle("Q{$row}")->applyFromArray([
                'font'         => ['bold' => true, 'color' => ['argb' => 'FF166534'], 'name' => 'Arial', 'size' => 9],
                'numberFormat' => ['formatCode' => '"Rp "#,##0'],
            ]);

            $prob      = (float) ($sheet->getCell("M{$row}")->getValue() ?? 0);
            $probColor = $prob >= 70 ? 'FF10B981' : ($prob >= 40 ? 'FFF59E0B' : 'FFF43F5E');
            $sheet->getStyle("M{$row}")->applyFromArray([
                'font'         => ['bold' => true, 'color' => ['argb' => $probColor], 'name' => 'Arial', 'size' => 9],
                'alignment'    => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'numberFormat' => ['formatCode' => '0.0"%"'],
            ]);

            $sheet->getStyle("N{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => 'FF166534'], 'name' => 'Arial', 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        }
    }

    private function applyTableBorders(Worksheet $sheet, int $lastRow): void
    {
        $sheet->getStyle("A5:Q{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFE2E8F0'],
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color'       => ['argb' => 'FF1E3A8A'],
                ],
            ],
        ]);
    }

    private function buildSummaryRow(Worksheet $sheet, int $lastRow): void
    {
        $sumRow = $lastRow + 2;

        $sheet->mergeCells("A{$sumRow}:B{$sumRow}");
        $sheet->setCellValue("A{$sumRow}", 'RINGKASAN');
        $sheet->setCellValue("C{$sumRow}", 'Total Penerima:');
        $sheet->setCellValue("D{$sumRow}", $this->rowCount . ' orang');
        // ← Total Penghasilan dihapus, diganti Total Dana BLT saja
        $sheet->setCellValue("P{$sumRow}", 'Total Dana BLT:');
        $sheet->setCellValue("Q{$sumRow}", "=SUM(Q6:Q{$lastRow})");

        $sheet->getStyle("A{$sumRow}:Q{$sumRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'name' => 'Arial', 'color' => ['argb' => 'FF1E3A8A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFF6FF']],
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E3A8A']],
            ],
        ]);

        $sheet->getStyle("Q{$sumRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getRowDimension($sumRow)->setRowHeight(18);
    }

    private function buildFootnote(Worksheet $sheet, int $lastRow): void
    {
        $noteRow = $lastRow + 4;
        $sheet->mergeCells("A{$noteRow}:Q{$noteRow}");
        $sheet->setCellValue(
            "A{$noteRow}",
            '* Dokumen ini digenerate otomatis oleh SiBantuDes — Sistem Informasi BLT Dana Desa, Desa Ngerong, Kab. Pasuruan.'
        );
        $sheet->getStyle("A{$noteRow}")->applyFromArray([
            'font'      => ['italic' => true, 'size' => 8, 'name' => 'Arial', 'color' => ['argb' => 'FF94A3B8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
    }

    private function applyPrintSettings(Worksheet $sheet): void
    {
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getPageMargins()
            ->setTop(0.75)->setBottom(0.75)
            ->setLeft(0.5)->setRight(0.5);

        $sheet->getHeaderFooter()
            ->setOddHeader('&C&B Laporan Penerima BLT-DD — Desa Ngerong')
            ->setOddFooter('&L&8SiBantuDes&C&8Halaman &P dari &N&R&8' . now()->format('d/m/Y'));
    }
}