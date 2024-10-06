<?php

namespace App\Http\Controllers;

use App\Exports\SDMExport;
use App\Http\Libraries\System;
use App\Models\Karyawan;
use App\Models\SDM;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SDMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $SDM = SDM::with(['panen', 'karyawan.jabatan'])->get();
            $karyawan = Karyawan::withCount('absensi')->get();

            // Buat associative array untuk karyawan dengan ID sebagai kunci
            $absensiMap = $karyawan->mapWithKeys(function ($item) {
                return [$item->id => $item->absensi_count];
            });

            // Tambahkan jumlah absensi ke setiap data SDM yang sesuai dengan ID karyawan
            $SDM->each(function ($item) use ($absensiMap) {
                $item->absen = $absensiMap[$item->karyawan_id] ?? 0; // Default 0 jika tidak ada absensi
            });

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch data successfuly',
                'data' => $SDM
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function findOne($id)
    {
        try {
            $SDM = SDM::with(['panen', 'karyawan'])->where('id', $id)->first();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch data successfuly',
                'data' => $SDM,
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');
        $tanggal = $request->get('tanggal');
        $panen_id = $request->get('panen_id');

        $export = new SDMExport([
            'greenhouse_id' => $greenhouse_id,
            'tanggal' => $tanggal,
            'panen_id' => $panen_id,
        ]);

        $tanggalFormat = '';

        if (!empty($tanggal)) {
            // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggal);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalFormat = $tanggal->format('d-m-Y');
        }

        return Excel::download($export, 'SDM-' . ($tanggalFormat ? $tanggalFormat : 'SemuaTanggal') . '-' . ($panen_id ? $panen_id : 'TanpaPanen') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'tanggal' => 'required',
            'catatan' => 'required',
            'nominal' => 'required | numeric',
            'panen_id' => 'required | numeric',
            'karyawan_id' => 'required | numeric'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) request()->all();

            $data = [
                'tanggal' => $dataReq->tanggal,
                'catatan' => $dataReq->catatan,
                'nominal' => $dataReq->nominal,
                'panen_id' => $dataReq->panen_id,
                'karyawan_id' => $dataReq->karyawan_id
            ];

            $sdm = SDM::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data successfuly',
                'data' => $sdm,
            ]);

        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            'tanggal' => 'required',
            'catatan' => 'required',
            'nominal' => 'required | numeric',
            'panen_id' => 'required | numeric',
            'karyawan_id' => 'required | numeric'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) request()->all();

            $data = [
                'tanggal' => $dataReq->tanggal,
                'catatan' => $dataReq->catatan,
                'nominal' => $dataReq->nominal,
                'panen_id' => $dataReq->panen_id,
                'karyawan_id' => $dataReq->karyawan_id
            ];

            $sdm = SDM::where('id', $id)->update($data);

            if ($sdm == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $sdm
            ]);

        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $sdm = SDM::where('id', $id)->delete();
            if ($sdm != null) {
                return System::response(200, [
                    'statusCode' => 200,
                    'message' => 'Delete data succesfuly',
                ]);
            }
            return System::response(400, [
                'statusCode' => 400,
                'message' => 'Delete data gagal, id tidak ditemukan!!',
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
}