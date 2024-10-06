<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Kontrol;
use App\Models\Pompa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KontrolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $kontrol = Kontrol::with('perangkat')->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $kontrol
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findOneWherePerangkat($id)
    {
        try {
            $kontrol = Kontrol::with('perangkat')->where('perangkat_id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $kontrol
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
            "suhu_min" => "required | numeric ",
            "suhu_max" => "required | numeric",
            "tds_min" => "required | numeric",
            "tds_max" => "required | numeric",
            "kelembaban_min" => "required | numeric",
            "kelembaban_max" => "required | numeric",
            "volume_min" => "required | numeric",
            "volume_max" => "required | numeric",
            "perangkat_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "suhu_min" => $dataReq->suhu_min,
            "suhu_max" => $dataReq->suhu_max,
            "tds_min" => $dataReq->tds_min,
            "tds_max" => $dataReq->tds_max,
            "kelembaban_min" => $dataReq->kelembaban_min,
            "kelembaban_max" => $dataReq->kelembaban_max,
            "volume_min" => $dataReq->volume_min,
            "volume_max" => $dataReq->intensitas_cahaya_max,
            "perangkat_id" => $dataReq->perangkat_id
        ];

        try {
            $kontrol = Kontrol::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "data" => $kontrol
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
            "suhu_min" => "required  | numeric",
            "suhu_max" => "required  | numeric",
            "tds_min" => "required | numeric",
            "tds_max" => "required | numeric",
            "kelembaban_min" => "required | numeric",
            "kelembaban_max" => "required | numeric",
            "volume_min" => "required | numeric",
            "volume_max" => "required | numeric",
            "perangkat_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "suhu_min" => $dataReq->suhu_min,
            "suhu_max" => $dataReq->suhu_max,
            "tds_min" => $dataReq->tds_min,
            "tds_max" => $dataReq->tds_max,
            "kelembaban_min" => $dataReq->kelembaban_min,
            "kelembaban_max" => $dataReq->kelembaban_max,
            "volume_min" => $dataReq->volume_min,
            "volume_max" => $dataReq->volume_max,
            "perangkat_id" => $dataReq->perangkat_id
        ];

        try {
            $kontrol = Kontrol::where('id', $id)->update($data);

            if ($kontrol == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly",
                "data" => $kontrol
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
            $kontrol = Kontrol::where("id", $id)->delete();

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
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function status($perangkatId)
    {
        $data = Pompa::where('perangkat_id', $perangkatId)
            ->first();

        if (!$data) return System::response(404, [
            'statusCode' => 404,
            'message' => 'Not found'
        ]);

        echo $data->status;
    }
}
