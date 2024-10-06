<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Kontrol;
use App\Models\Perangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $perangkat = Perangkat::with('greenhouse')->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $perangkat
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
            $perangkat = Perangkat::with('greenhouse')->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $perangkat
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findOneGreenhouse($id)
    {
        try {
            $perangkat = Perangkat::with('greenhouse')->where('greenhouse_id', $id)->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $perangkat
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama" => "required",
            "keterangan" => "required",
            "greenhouse_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "nama" => $dataReq->nama,
            "keterangan" => $dataReq->keterangan,
            "greenhouse_id" => $dataReq->greenhouse_id
        ];


        try {
            $perangkat = Perangkat::create($data);
            $dataKontrol = [
                "suhu_min" => 100,
                "suhu_max" => 100,
                "tds_min" => 100,
                "tds_max" => 100,
                "kelembaban_min" => 100,
                "kelembaban_max" => 100,
                "volume_min" => 20,
                "volume_max" => 100,
                "perangkat_id" => $perangkat->id
            ];
            Kontrol::create($dataKontrol);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "data" => $perangkat
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "nama" => "required",
            "keterangan" => "required",
            "greenhouse_id" => "required | numeric"
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "nama" => $dataReq->nama,
            "keterangan" => $dataReq->keterangan,
            "greenhouse_id" => $dataReq->greenhouse_id
        ];

        try {
            $perangkat = Perangkat::where('id', $id)->update($data);

            if ($perangkat == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $perangkat
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
            $perangkat = Perangkat::where("id", $id)->delete();
            if ($perangkat != null)
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
