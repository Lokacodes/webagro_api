<?php

namespace App\Exports;

use App\Models\Diagnosa;
use App\Models\GreenHouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataDiagnosaExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $waktu = $this->attribute['waktu'];
        $greenhouse_id = $this->attribute['greenhouse_id'];

        $query = Diagnosa::with([
            'greenhouse',
            'penyakit',
            'riwayat_penyakit.penyakit',
            'riwayat_penyakit' => function ($query) {
                $query->orderBy('nilai', 'desc'); // Mengurutkan nilai dari terbesar ke terkecil
            },
            'panen',
            'tanaman.jenis_tanaman'
        ]);

        if (!empty($greenhouse_id)) {
            $query->where('greenhouse_id', $greenhouse_id);
        }
        if (!empty($waktu)) {
            $waktuSekarang = Carbon::now();
            switch ($waktu) {
                case 1: // Hari ini
                    $query->whereDate('created_at', $waktuSekarang->format('Y-m-d'));
                    break;

                case 2: // Minggu ini
                    $startOfWeek = $waktuSekarang->startOfWeek()->format('Y-m-d');
                    $endOfWeek = $waktuSekarang->endOfWeek()->format('Y-m-d');
                    $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                    break;

                case 3: // Bulan ini
                    $startOfMonth = $waktuSekarang->startOfMonth()->format('Y-m-d');
                    $endOfMonth = $waktuSekarang->endOfMonth()->format('Y-m-d');
                    $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                    break;
            }
        }

        $daftarDiagnosa = $query->get();

        // Build Data
        $result = [];
        foreach ($daftarDiagnosa as $diagnosa) {
            $result[] = [
                "nama" => $diagnosa->nama,
                "tanggal" => $diagnosa->tanggal,
                "panen" => $diagnosa->panen->nama,
                "tanaman" => $diagnosa->tanaman->jenis_tanaman->nama . ' (' . $diagnosa->tanaman->kode . ')',
                "penyakit" => $diagnosa->riwayat_penyakit[0]->penyakit->nama ?? 'N/A',
                "nilai" => $diagnosa->riwayat_penyakit[0]->nilai ?? 0,
                "bobot" => $diagnosa->riwayat_penyakit[0]->bobot ?? 0,
                "greenhouse" => $diagnosa->greenhouse->nama,
            ];
        }

        return new Collection($result);
    }


    public function headings(): array
    {
        $waktu = $this->attribute['waktu'];
        $greenhouse_id = $this->attribute['greenhouse_id'];

        $waktuFormat = "";
        $greenhouse_nama = "";

        if (!empty($waktu)) {
            $waktuSekarang = Carbon::now();
            switch ($waktu) {
                case 1: // Hari ini
                    $waktuFormat = 'Hari ini - ' . $waktuSekarang->format('d-m-Y');
                    break;

                case 2: // Minggu ini
                    $startOfWeek = $waktuSekarang->startOfWeek()->format('d-m-Y');
                    $endOfWeek = $waktuSekarang->endOfWeek()->format('d-m-Y');
                    $waktuFormat = 'Minggu ini - ' . $startOfWeek . ' sampai ' . $endOfWeek;
                    break;

                case 3: // Bulan ini
                    $waktuFormat = 'Bulan ini - ' . $waktuSekarang->format('F Y');
                    break;
            }
        }

        if (!empty($greenhouse_id)) {
            $greenhouse = GreenHouse::select('nama')->where('id', $greenhouse_id)->first();
            if ($greenhouse) {
                $greenhouse_nama = $greenhouse->nama;
            }
        }

        return [
            ["Data Diagnosa"],
            $waktuFormat ? [$waktuFormat] : [],
            $greenhouse_nama ? [$greenhouse_nama] : [],
            [],
            [
                'NAMA',
                'TANGGAL',
                'PANEN',
                'TANAMAN',
                'PENYAKIT',
                'NILAI',
                'BOBOT',
                'GREENHOUSE'
            ]
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // Merge
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

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