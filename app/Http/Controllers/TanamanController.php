<?php

namespace App\Http\Controllers;

use App\Exports\TanamanExport;
use App\Http\Libraries\System;
use App\Models\Tanaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $tanaman = Tanaman::with(['jenis_tanaman', 'greenhouse'])->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => "Fetch all data succes",
                'data' => $tanaman
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function findOne($id)
    {
        try {
            $absensi = Tanaman::with(['jenis_tanaman', 'greenhouse'])->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch Data successfuly",
                "data" => $absensi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function findTanamanGreenhouse($id)
    {
        try {
            $absensi = Tanaman::with(['jenis_tanaman', 'greenhouse'])->where('greenhouse_id', $id)->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch Data successfuly",
                "data" => $absensi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');

        $export = new TanamanExport([
            'greenhouse_id' => $greenhouse_id,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
        ]);

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $tanggal_awal = Carbon::createFromFormat('Y-m-d', $tanggal_awal);
            $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $tanggal_akhir);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalAwalFormat = $tanggal_awal->format('d-m-Y');
            $tanggalAkhirFormat = $tanggal_akhir->format('d-m-Y');
        }

        // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon

        return Excel::download($export, 'Tanaman-' . (!empty($tanggal_awal) && !empty($tanggal_akhir) ? $tanggalAwalFormat . ' - ' . $tanggalAkhirFormat : 'SemuaTanggal') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'kode' => 'required',
            'pertumbuhan' => 'required',
            'jenis_tanaman_id' => 'required | numeric',
            'greenhouse_id' => 'required | numeric',
        ]);

        if ($validate->fails()) {
            return System::badRequest($validate);
        }


        $data = [
            'kode' => $request->input('kode'),
            'pertumbuhan' => $request->input('pertumbuhan'),
            'jenis_tanaman_id' => $request->input('jenis_tanaman_id'),
            'greenhouse_id' => $request->input('greenhouse_id')
        ];

        try {
            $tanaman = Tanaman::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data succsessful!',
                'data' => $tanaman
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'kode' => 'required',
            'pertumbuhan' => 'required',
            'jenis_tanaman_id' => 'required | numeric',
            'greenhouse_id' => 'required | numeric',
        ]);

        if ($validate->fails()) {
            return System::badRequest($validate);
        }


        $data = [
            'kode' => $request->input('kode'),
            'pertumbuhan' => $request->input('pertumbuhan'),
            'jenis_tanaman_id' => $request->input('jenis_tanaman_id'),
            'greenhouse_id' => $request->input('greenhouse_id')
        ];

        try {
            $tanaman = Tanaman::where('id', $id)->update($data);

            if ($tanaman == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $tanaman
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tanaman = Tanaman::where('id', $id)->delete();
            if ($tanaman != null)
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
                'statusCode' => 500,
                'message' => 'Error delete data'
            ]);
        }
    }
}