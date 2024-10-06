<?php

namespace App\Exports;

use App\Models\KontrolLingkungan;
use App\Models\GreenHouse;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KontrolLingkunganExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
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
        $greenhouse_id = $this->attribute['greenhouse_id'];

        $sensor = Sensor::with("perangkat")
            ->whereHas('perangkat', function ($q) use ($greenhouse_id) {
                $q->where('greenhouse_id', $greenhouse_id);
            })
            ->orderBy('created_at', 'DESC')
            ->get();


        // Build Data
        $result = [];
        foreach ($sensor as $val) {
            $result[] = [
                'sensor_suhu' => $val->sensor_suhu,
                'sensor_kelembaban' => $val->sensor_kelembaban,
                'sensor_ldr' => $val->sensor_ldr,
                'sensor_tds' => $val->sensor_tds,
                'sensor_waterflow' => $val->sensor_waterflow,
                'keterangan' => $val->keterangan,
                'perangkat_id' => $val->perangkat->nama,
            ];
        }

        return new Collection($result);
    }

    public function headings(): array
    {
        $tanggal = Carbon::now();
        $tanggalFormat = $tanggal->format('d-m-Y');

        $greenhouse_nama = "";


        if (!empty($this->attribute['greenhouse_id'])) {
            $greenhouse_nama = GreenHouse::select('nama')->where('id', $this->attribute['greenhouse_id'])->first();
        }

        return [
            ["KONTROL LINGKUNGAN"],
            ["{$tanggalFormat}"],
            !empty($this->attribute['greenhouse_id']) ? ["{$greenhouse_nama->nama}"] : ["Semua Greenhouse"],
            [],
            [
                'SENSOR SUHU',
                'SENSOR KELEMBABAN',
                'SENSOR LDR',
                'SENSOR TDS',
                'SENSOR WATERFLOW',
                'KETERANGAN',
                'NAMA PERANGKAT',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

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