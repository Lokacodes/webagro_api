<?php

namespace App\Exports;

use App\Models\GreenHouse;
use App\Models\Pendapatan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendapatanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    private $attribute;

    public function __construct(array $attr)
    {
        $this->attribute = $attr;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $tanggal = $this->attribute['tanggal'];
        $panen_id = $this->attribute['panen_id'];
        $greenhouse_id = $this->attribute['greenhouse_id'];

        $query = Pendapatan::with([
            'panen',
            'satuan'
        ]);

        if (!empty($greenhouse_id)) {
            $query->where('greenhouse_id', $greenhouse_id);
        }
        if (!empty($tanggal)) {
            $query->where('tanggal', $tanggal);
        }
        if (!empty($panen_id)) {
            $query->where('panen_id', $panen_id);
        }

        $daftarPendapatan = $query->get();

        // Build Data
        $result = [];
        foreach ($daftarPendapatan as $pendapatan) {
            $result[] = [
                'tanggal' => $pendapatan->tanggal,
                'nama_pembeli' => $pendapatan->nama_pembeli,
                'alamat_pembeli' => $pendapatan->alamat_pembeli,
                'produk' => $pendapatan->produk,
                'jumlah' => $pendapatan->jumlah . ' ' . ($pendapatan->satuan ? $pendapatan->satuan->nama : ''),
                'nominal' => $pendapatan->nominal,
                'catatan' => $pendapatan->catatan,
                'panen' => $pendapatan->panen ? $pendapatan->panen->nama : ''
            ];
        }

        return new Collection($result);
    }


    public function headings(): array
    {
        $tanggal = $this->attribute['tanggal'];

        $tanggalFormat = "";

        if (!empty($tanggal)) {
            // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggal);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalFormat = $tanggal->format('d-m-Y');
        }

        $greenhouse_nama = "";


        if (!empty($this->attribute['greenhouse_id'])) {
            $greenhouse_nama = GreenHouse::select('nama')->where('id', $this->attribute['greenhouse_id'])->first();
        }
        return [
            ["PENDAPATAN"],
            $tanggalFormat ? ["{$tanggalFormat}"] : [],
            $greenhouse_nama ? ["{$greenhouse_nama->nama}"] : [],
            [],
            [
                'TANGGAL',
                'NAMA PEMBELI',
                'ALAMAT PEMBELI',
                'PRODUK',
                'QTY',
                'NOMINAL',
                'CATATAN',
                'PANEN',
            ]
        ];

    }

    public function styles(Worksheet $sheet)
    {
        // Merge
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        return [
            1 => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
            ],
            2 => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            3 => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            5 => [
                'font' => [
                    'bold' => true,
                ]
            ]
        ];
    }
}