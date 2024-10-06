<?php

namespace App\Exports;

use App\Models\Diagnosa;
use App\Models\GreenHouse;
use App\Models\Riwayat;
use App\Models\RiwayatPenyakit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiwayatKonsultasiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $diagnosa_id = $this->attribute['diagnosa_id'];

        $dataDiagnosa = Diagnosa::with([
            'riwayat_penyakit' => function ($query) {
                $query->orderBy('nilai', 'DESC')->with(['penyakit.post']);
            },
            'riwayat' => function ($query) {
                $query->with(['gejala']);
            }
        ])->where('id', $diagnosa_id)->first();

        $gejalaCount = count($dataDiagnosa->riwayat);
        $penyakitCount = count($dataDiagnosa->riwayat_penyakit);

        $maxCount = max($gejalaCount, $penyakitCount);

        $result = [];

        for ($i = 0; $i < $maxCount; $i++) {
            $gejala = $dataDiagnosa->riwayat[$i]->gejala ?? null;
            $penyakit = $dataDiagnosa->riwayat_penyakit[$i]->penyakit ?? null;

            $result[] = [
                $gejala ? $gejala->kode : '',
                $gejala ? $gejala->nama : '',
                $gejala ? $dataDiagnosa->riwayat[$i]->cf->kondisi : '',
                '',
                '',
                $penyakit ? $penyakit->kode : '',
                $penyakit ? $penyakit->nama : '',
                $penyakit ? ($dataDiagnosa->riwayat_penyakit[$i]->nilai * 100) . '%' : '',
                $penyakit ? $penyakit->post[0]->saran : '',
                $penyakit ? $penyakit->post[0]->detail : '',
            ];
        }

        return new Collection($result);
    }

    public function headings(): array
    {
        $diagnosa = Diagnosa::with(['greenhouse', 'penyakit', 'panen', 'tanaman.jenis_tanaman'])->where('id', $this->attribute['diagnosa_id'])->first();

        return [
            ["Data Diagnosa"],
            ["{$diagnosa->nama}"],
            ["Data Gejala", '', '', '', '', "Data Penyakit"],
            [],
            [
                'KODE GEJALA',
                'NAMA GEJALA',
                'PILIHAN USER',
                '',
                '',
                'KODE PENYAKIT',
                'NAMA PENYAKIT',
                'PRESENTASE KEMUNGKINAN',
                "SOLUSI/PENANGANAN",
                "DETAIL"
            ]
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // Merge
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:C3');
        $sheet->mergeCells('F3:L3');

        $sheet->getColumnDimension('I')->setWidth(5); // Set width for column I
        $sheet->getColumnDimension('J')->setWidth(5); // Set width for column J

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
                ],
            ],
            '6:100' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Teks dimulai dari kiri atas
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Teks dimulai dari kiri atas
                    'wrapText' => true, // Enable word wrap
                ],
            ],

        ];
    }
}