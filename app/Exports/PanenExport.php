<?php

namespace App\Exports;

use App\Models\Panen;
use App\Models\GreenHouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PanenExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $greenhouse_id = $this->attribute['greenhouse_id'];

        $query = Panen::with([
            'greenhouse'
        ]);

        if (!empty($greenhouse_id)) {
            $query->where('greenhouse_id', $greenhouse_id);
        }
        if (!empty($tanggal)) {
            $query->where('created_at', $tanggal);
        }

        $daftarPanen = $query->get();

        // Build Data
        $result = [];
        foreach ($daftarPanen as $panen) {
            $result[] = [
                'nama' => $panen->nama,
                'tanggal_tanam' => $panen->tanggal_tanam,
                'tanggal_panen' => $panen->tanggal_panen,
                'greenhouse' => $panen->greenhouse->nama
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
            ["PANEN"],
            $tanggalFormat ? ["{$tanggalFormat}"] : [],
            $greenhouse_nama ? ["{$greenhouse_nama->nama}"] : [],
            [],
            [
                'NAMA',
                'TANGGAL TANAM',
                'TANGGAL PANEN',
                'GREENHOUSE'
            ]
        ];

    }

    public function styles(Worksheet $sheet)
    {
        // Merge
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');

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