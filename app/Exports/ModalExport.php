<?php

namespace App\Exports;

use App\Models\GreenHouse;
use App\Models\Modal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ModalExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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

        $query = Modal::with('panen.greenhouse');

        if (!empty($greenhouse_id)) {
            $query->where('greenhouse_id', $greenhouse_id);
        }
        if (!empty($tanggal)) {
            $query->where('tanggal', $tanggal);
        }
        if (!empty($panen_id)) {
            $query->where('panen_id', $panen_id);
        }

        $daftarModal = $query->get();

        // Build Data
        $result = [];
        foreach ($daftarModal as $modal) {
            $result[] = [
                'tanggal' => $modal->tanggal,
                'catatan' => $modal->catatan,
                'nominal' => $modal->nominal,
                'greenhouse' => $modal->panen->greenhouse->nama,
                'panen' => $modal->panen->nama
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
            ["MODAL"],
            $tanggalFormat ? ["{$tanggalFormat}"] : [],
            $greenhouse_nama ? ["{$greenhouse_nama->nama}"] : [],
            [],
            [
                'TANGGAL',
                'NOMINAL',
                'CATATAN',
                'GREENHOUSE',
                'PANEN'
            ]
        ];

    }

    public function styles(Worksheet $sheet)
    {
        // Merge
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');

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