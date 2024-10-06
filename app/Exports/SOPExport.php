<?php

namespace App\Exports;

use App\Models\SOP;
use App\Models\GreenHouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SOPExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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

        // $daftarPupuk = Pupuk::select('tanggal','nama_pembeli','alamat_pembeli','produk','jumlah','nominal as Qty','catatan','panen.nama','satuan.nama as nama_satuan')->with(['panen','satuan'])->where('greenhouse_id', $greenhouse_id)->get()->toArray();

        $query = SOP::with(["karyawan.jabatan", "tanaman"]);

        if (!empty($greenhouse_id)) {
            $query->whereHas('tanaman', function ($q) use ($greenhouse_id) {
                $q->where('greenhouse_id', $greenhouse_id);
            });

        }

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
        }

        $daftarSOP = $query->get();


        // Build Data
        $result = [];
        foreach ($daftarSOP as $sop) {
            $result[] = [
                'tanggal' => $sop->tanggal,
                'nama' => $sop->karyawan->nama,
                'tugas' => $sop->tugas,
                'catatan' => $sop->catatan,
                'kode' => $sop->tanaman->kode,
                'greenhouse' => $sop->tanaman->greenhouse->nama
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
            ["SOP"],
            !empty($tanggal_awal) && !empty($tanggal_akhir) ? ["{$tanggalAwalFormat} - {$tanggalAkhirFormat}"] : ["Semua Tanggal"],
            !empty($this->attribute['greenhouse_id']) ? ["{$greenhouse_nama->nama}"] : ["Semua Greenhouse"],
            [],
            [
                'TANGGAL',
                'NAMA KARYAWAN',
                'TUGAS',
                'CATATAN',
                'KODE TANAMAN',
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