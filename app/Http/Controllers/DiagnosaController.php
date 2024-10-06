<?php

namespace App\Http\Controllers;

use App\Exports\DataDiagnosaExport;
use App\Exports\RiwayatKonsultasiExport;
use App\Http\Libraries\System;
use App\Models\Diagnosa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DiagnosaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $diagnosa = Diagnosa::with([
                'greenhouse',
                'penyakit',
                'riwayat_penyakit.penyakit',
                'riwayat_penyakit' => function ($query) {
                    $query->orderBy('nilai', 'desc'); // Mengurutkan nilai dari terbesar ke terkecil
                },
                'panen',
                'tanaman.jenis_tanaman'
            ])->get();


            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $diagnosa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }
    public function findOne($id)
    {
        try {
            $diagnosa = Diagnosa::with(['greenhouse', 'penyakit', 'panen', 'tanaman.jenis_tanaman'])->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $diagnosa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findAllGreenhouse($id)
    {
        try {
            $diagnosa = Diagnosa::with([
                'greenhouse',
                'penyakit',
                'riwayat_penyakit.penyakit',
                'riwayat_penyakit' => function ($query) {
                    $query->orderBy('nilai', 'desc'); // Mengurutkan nilai dari terbesar ke terkecil
                },
                'panen',
                'tanaman.jenis_tanaman'
            ])->where('greenhouse_id', $id)->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $diagnosa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');
        $waktu = $request->get('waktu');

        $export = new DataDiagnosaExport([
            'greenhouse_id' => $greenhouse_id,
            'waktu' => $waktu
        ]);

        $waktuFormat = '';

        if (!empty($waktu)) {
            $waktuSekarang = Carbon::now();

            // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon
            if ($waktu == 1) {
                // Tanggal Sekarang
                $waktuFormat = 'Bulan-' . $waktuSekarang->format('m') . '-Tahun-' . $waktuSekarang->format('Y');
            } else if ($waktu == 2) {
                // Minggu Sekarang
                $waktuFormat = 'Minggu-ke-' . $waktuSekarang->weekOfMonth . '-Bulan-' . $waktuSekarang->format('m') . '-Tahun-' . $waktuSekarang->format('Y');
            } else if ($waktu == 3) {
                // Bulan Sekarang
                $waktuFormat = 'Bulan-' . $waktuSekarang->format('m') . '-Tahun-' . $waktuSekarang->format('Y');
            }

            Log::info($waktuFormat);

        }
        return Excel::download($export, 'Data-Diagnosa-' . ($waktuFormat ? $waktuFormat : 'SemuaTanggal') . '-' . ($greenhouse_id ? $greenhouse_id : '') . '.xlsx');
    }


    public function exportExcelKonsultasi(Request $request)
    {
        $diagnosa_id = $request->get('diagnosa_id');

        $diagnosa = Diagnosa::with(['greenhouse', 'penyakit', 'panen', 'tanaman.jenis_tanaman'])->where('id', $diagnosa_id)->first();

        $export = new RiwayatKonsultasiExport([
            'diagnosa_id' => $diagnosa_id
        ]);


        return Excel::download($export, 'Riwayat-Konsultasi-' . $diagnosa->nama . '-' . $diagnosa_id . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama" => "required",
            "tanggal" => "required",
            "gambar" => "required",
            "greenhouse_id" => "required | numeric",
            "panen_id" => "required | numeric",
            "tanaman_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->gambar;
            $file->move(public_path('uploads/diagnosa'), $filename);

            $data = [
                "nama" => $request->nama,
                "tanggal" => $request->tanggal,
                "gambar" => $request->gambar,
                "greenhouse_id" => $request->greenhouse_id,
                "panen_id" => $request->panen_id,
                "tanaman_id" => $request->tanaman_id
            ];

            try {
                $diagnosa = Diagnosa::create($data);

                return System::response(201, [
                    "statusCode" => 201,
                    "message" => "Create data successfuly",
                    "data" => $diagnosa
                ]);
            } catch (\Throwable $th) {
                return System::response(500, [
                    "statusCode" => 500,
                    "message" => $th->getMessage()
                ]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "nama" => "required",
            "tanggal" => "required",
            "gambar" => "required",
            "greenhouse_id" => "required | numeric",
            "panen_id" => "required | numeric",
            "tanaman_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataLama = Diagnosa::where('id', $id)->first();

        if ($dataLama->gambar != $request->gambar) {
            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (File::exists(public_path('uploads/diagnosa/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/diagnosa/' . $dataLama->gambar));
            }

            // Upload gambar baru
            $file = $request->file('image');
            $filename = $request->gambar; // Anda mungkin perlu menyesuaikan ini
            $file->move(public_path('uploads/diagnosa'), $filename);
        }

        $data = [
            "nama" => $request->nama,
            "tanggal" => $request->tanggal,
            "gambar" => $request->gambar,
            "greenhouse_id" => $request->greenhouse_id,
            "panen_id" => $request->panen_id,
            "tanaman_id" => $request->tanaman_id
        ];

        try {
            $diagnosa = Diagnosa::where('id', $id)->update($data);

            if ($diagnosa == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly",
                "data" => $diagnosa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $dataLama = Diagnosa::where('id', $id)->first();

            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (File::exists(public_path('uploads/diagnosa/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/diagnosa/' . $dataLama->gambar));
            }

            $diagnosa = Diagnosa::where("id", $id)->delete();

            if ($diagnosa != null)
                return System::response(200, [
                    'statusCode' => 200,
                    'message' => 'Delete data successfuly'
                ]);

            return System::response(400, [
                'statusCode' => 400,
                'message' => 'Delete data gagal, id tidak ditemukan!!'
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }
}