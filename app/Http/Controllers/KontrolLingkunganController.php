<?php

namespace App\Http\Controllers;

use App\Exports\KontrolLingkunganExport;
use App\Http\Libraries\System;
use App\Models\KontrolLingkungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class KontrolLingkunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $kontrol = KontrolLingkungan::with(['tanaman.jenis_tanaman', 'pupuk.jenis'])->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => "Fetch all data succes",
                'data' => $kontrol
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
            $kontrol = KontrolLingkungan::with(['tanaman.jenis_tanaman', 'pupuk.jenis'])->where('id', $id)->first();

            return System::response(200, [
                'statusCode' => 200,
                'message' => "Fetch all data succes",
                'data' => $kontrol
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');

        $export = new KontrolLingkunganExport([
            'greenhouse_id' => $greenhouse_id,
        ]);
        $tanggal = Carbon::now();

        $tanggalFormat = $tanggal->format('d-m-Y');


        return Excel::download($export, 'Kontrol-Lingkungan-' . $tanggalFormat . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tanggal' => 'required',
            'catatan' => 'required',
            'pupuk_id' => 'required',
            'tanaman_id' => 'required',
        ]);

        if ($validate->fails()) {
            return System::badRequest($validate);
        }


        $data = [
            'tanggal' => $request->input('tanggal'),
            'catatan' => $request->input('catatan'),
            'pupuk_id' => $request->input('pupuk_id'),
            'tanaman_id' => $request->input('tanaman_id'),
        ];

        try {
            $kontrol = KontrolLingkungan::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data succsessful!',
                'data' => $kontrol
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KontrolLingkungan $kontrolLingkungan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KontrolLingkungan $kontrolLingkungan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'tanggal' => 'required',
            'catatan' => 'required',
            'pupuk_id' => 'required',
            'tanaman_id' => 'required',
        ]);

        if ($validate->fails()) {
            return System::badRequest($validate);
        }


        $data = [
            'tanggal' => $request->input('tanggal'),
            'catatan' => $request->input('catatan'),
            'pupuk_id' => $request->input('pupuk_id'),
            'tanaman_id' => $request->input('tanaman_id'),
        ];

        try {
            $kontrol = KontrolLingkungan::where('id', $id)->update($data);

            if ($kontrol == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Update data succsessful!',
                'data' => $kontrol
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
            $kontrol = KontrolLingkungan::where('id', $id)->delete();
            if ($kontrol != null)
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