<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Http\Libraries\System;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $karyawan = Karyawan::with(['jabatan', 'user.role', 'greenhouse'])->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => "Fetch all data succes",
                'data' => $karyawan
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
            $absensi = Karyawan::with(['jabatan', 'user', 'greenhouse'])->where('id', $id)->first();

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

    public function findOneUser($id)
    {
        try {
            $absensi = Karyawan::with(['jabatan', 'user', 'greenhouse'])->where('user_id', $id)->first();

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

        $export = new KaryawanExport([
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

        return Excel::download($export, 'Karyawan-' . (!empty($tanggal_awal) && !empty($tanggal_akhir) ? $tanggalAwalFormat . ' - ' . $tanggalAkhirFormat : 'SemuaTanggal') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nik' => 'required | digits:16  | numeric',
            'nama' => 'required',
            'alamat' => 'required',
            'jkel' => 'required',
            'jabatan_id' => 'required | numeric',
            'user_id' => 'required | numeric',
            'greenhouse_id' => 'required | numeric',
        ]);

        if ($validate->fails()) {
            return System::badRequest($validate);
        }

        $data = [
            'nama' => $request->input('nama'),
            'nik' => $request->input('nik'),
            'alamat' => $request->input('alamat'),
            'jkel' => $request->input('jkel'),
            'jabatan_id' => $request->input('jabatan_id'),
            'user_id' => $request->input('user_id'),
            'greenhouse_id' => $request->input('greenhouse_id')
        ];

        try {
            $Karyawan = Karyawan::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data succsessful!',
                'data' => $Karyawan
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }

    }
    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nik' => 'required | digits:16  | numeric',
            'nama' => 'required',
            'alamat' => 'required',
            'jkel' => 'required',
            'jabatan_id' => 'required | numeric',
            'user_id' => 'required | numeric',
            'greenhouse_id' => 'required | numeric',
        ]);

        if ($validate->fails()) {
            return System::badRequest($validate);
        }

        $dataLama = Karyawan::where('id', $id)->first();

        if ($dataLama->gambar != $request->gambar) {
            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (file::exists(public_path('uploads/karyawan/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/karyawan/' . $dataLama->gambar));
            }

            // Upload gambar baru
            $file = $request->file('image');
            $filename = $request->gambar; // Anda mungkin perlu menyesuaikan ini
            $file->move(public_path('uploads/karyawan'), $filename);
        }

        $data = [
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'jkel' => $request->jkel,
            'jabatan_id' => $request->jabatan_id,
            'user_id' => $request->user_id,
            'greenhouse_id' => $request->greenhouse_id,
            'gambar' => $request->gambar
        ];

        try {
            $Karyawan = Karyawan::where('id', $id)->update($data);

            if ($Karyawan == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Update data succsessful!',
                'data' => $Karyawan
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
                'request' => $request->input()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $karyawan = Karyawan::where('id', $id)->delete();

            if ($karyawan != null)
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