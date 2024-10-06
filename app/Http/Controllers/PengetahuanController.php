<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Pengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengetahuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $pengetahuan = Pengetahuan::with(['gejala', 'penyakit'])->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $pengetahuan
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
            $pengetahuan = Pengetahuan::with(['gejala', 'penyakit'])->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $pengetahuan
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function getPengetahuanGejalaArray(Request $request)
    {
        try {
            $gejala_id = $request->input('gejala_id');

            if (!is_array($gejala_id)) {
                throw new \Exception("Parameter gejala_id harus berupa array.");
            }

            $pengetahuan_id = [];

            foreach ($gejala_id as $value) {
                $tempData = Pengetahuan::where("gejala_id", $value)->get();
                $pengetahuan_id[] = $tempData;
            }

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Array Id Pengetahuan Berhasil didapatkan",
                "data" => $pengetahuan_id
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
            "mb" => "required | numeric",
            "md" => "required | numeric",
            "penyakit_id" => "required | numeric",
            "gejala_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "mb" => $dataReq->mb,
            "md" => $dataReq->md,
            "penyakit_id" => $dataReq->penyakit_id,
            "gejala_id" => $dataReq->gejala_id
        ];

        try {
            $pengetahuan = Pengetahuan::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "data" => $pengetahuan
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
            "mb" => "required | numeric",
            "md" => "required | numeric",
            "penyakit_id" => "required | numeric",
            "gejala_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "mb" => $dataReq->mb,
            "md" => $dataReq->md,
            "penyakit_id" => $dataReq->penyakit_id,
            "gejala_id" => $dataReq->gejala_id
        ];
        try {
            $pengetahuan = Pengetahuan::where('id', $id)->update($data);

            if ($pengetahuan == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $pengetahuan
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
            $pengetahuan = Pengetahuan::where("id", $id)->delete();

            if ($pengetahuan != null)
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