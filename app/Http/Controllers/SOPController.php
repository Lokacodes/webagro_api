<?php

namespace App\Http\Controllers;

use App\Exports\SOPExport;
use App\Http\Libraries\System;
use App\Models\SOP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SOPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $sop = SOP::with(["karyawan.jabatan", "tanaman", 'greenhouse'])->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data Successful",
                "data" => $sop
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
            $sop = SOP::with(["karyawan.jabatan", "tanaman", 'greenhouse'])->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data Successful",
                "data" => $sop
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findSOPKaryawan($id)
    {
        try {
            $sop = SOP::with(["karyawan.jabatan", "tanaman.jenis_tanaman", 'greenhouse'])->where('karyawan_id', $id)->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data Successful",
                "data" => $sop
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
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');

        $export = new SOPExport([
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

        return Excel::download($export, 'SOP-' . (!empty($tanggal_awal) && !empty($tanggal_akhir) ? $tanggalAwalFormat . ' - ' . $tanggalAkhirFormat : 'SemuaTanggal') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            "tanggal" => "required",
            "tugas" => "required",
            "catatan" => "required",
            "karyawan_id" => "required | numeric",
            "greenhouse_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $data = [
            "tanggal" => $request->input("tanggal"),
            "tugas" => $request->input("tugas"),
            "catatan" => $request->input("catatan"),
            "karyawan_id" => $request->input("karyawan_id"),
            "tanaman_id" => $request->input("tanaman_id"),
            "greenhouse_id" => $request->input("greenhouse_id"),
        ];

        try {
            $sop = SOP::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly!!",
                "data" => $sop
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            "tanggal" => "required",
            "tugas" => "required",
            "catatan" => "required",
            "karyawan_id" => "required | numeric",
            "greenhouse_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $data = [
            "tanggal" => $request->input("tanggal"),
            "tugas" => $request->input("tugas"),
            "catatan" => $request->input("catatan"),
            "karyawan_id" => $request->input("karyawan_id"),
            "tanaman_id" => $request->input("tanaman_id"),
            "greenhouse_id" => $request->input("greenhouse_id")
        ];

        try {
            $sop = SOP::where("id", $id)->update($data);

            if ($sop == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $sop
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
    public function destroy(Request $request, $id)
    {
        try {
            $sop = SOP::where("id", $id)->delete();

            if ($sop != null)
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