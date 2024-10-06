<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\GreenHouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $tanggal_awal = $this->attribute['tanggal_awal'];
        $tanggal_akhir = $this->attribute['tanggal_akhir'];
        $greenhouse_id = $this->attribute['greenhouse_id'];


        $query = Absensi::with([
            'karyawan.jabatan',
        ]);

        if (!empty($greenhouse_id)) {
            $query->whereHas('karyawan', function ($q) use ($greenhouse_id) {
                $q->where('greenhouse_id', $greenhouse_id);
            });

        }

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
        }

        $daftarAbsen = $query->get();

        // $resultSort = [];

        // foreach ($daftarAbsen as $data) {
        //     $karyawan_id = $data['karyawan_id'];
        //     $status = $data['status'];

        //     if (!isset($result[$karyawan_id])) {
        //         $result[$karyawan_id] = [
        //             'karyawan_id' => $karyawan_id,
        //             'total_masuk' => 0,
        //             'entries' => []
        //         ];
        //     }

        //     $result[$karyawan_id]['entries'][] = $data;

        //     if ($status === 'MASUK') {
        //         $result[$karyawan_id]['total_masuk']++;
        //     }
        // }


        // Build Data
        $result = [];
        foreach ($daftarAbsen as $absen) {
            $result[] = [
                'nama_karyawan' => $absen->karyawan->nama,
                'jabatan' => $absen->karyawan->jabatan->nama,
                'status' => $absen->status,
                'keterangan' => $absen->catatan,
                'greenhouse' => $absen->karyawan->greenhouse->nama
            ];
        }

        return new Collection($result);
    }

    public function headings(): array
    {
        $tanggal_awal = $this->attribute['tanggal_awal'];
        $tanggal_akhir = $this->attribute['tanggal_akhir'];

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon
            $tanggal_awal = Carbon::createFromFormat('Y-m-d', $tanggal_awal);
            $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $tanggal_akhir);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalAwalFormat = $tanggal_awal->format('d-m-Y');
            $tanggalAkhirFormat = $tanggal_akhir->format('d-m-Y');
        }

        $greenhouse_nama = "";


        if (!empty($this->attribute['greenhouse_id'])) {
            $greenhouse_nama = GreenHouse::select('nama')->where('id', $this->attribute['greenhouse_id'])->first();
        }

        return [
            ["RREKAP ABSEN"],
            !empty($tanggal_awal) && !empty($tanggal_akhir) ? ["{$tanggalAwalFormat} - {$tanggalAkhirFormat}"] : ["Semua Tanggal"],
            !empty($this->attribute['greenhouse_id']) ? ["{$greenhouse_nama->nama}"] : ["Semua Greenhouse"],
            [],
            [
                'NAMA KARYAWAN',
                'JABATAN',
                'STATUS',
                'KETERANGAN',
                'GREENHOUSE',
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