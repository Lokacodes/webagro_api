<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Pompa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PompaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findOne($id)
    {
        try {
            $pompa = Pompa::with('perangkat')->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data succesfuly",
                "data" => $pompa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "keterangan" => "required",
            "perangkat_id" => "required",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->input();

            $data = [
                "status" => $dataReq->status,
                "auto" => "MATI",
                "keterangan" => $dataReq->keterangan,
                "perangkat_id" => $dataReq->perangkat_id,
            ];

            $pompa = Pompa::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "data" => $pompa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "auto" => "required",
            "keterangan" => "required",
            "perangkat_id" => "required",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->input();

            $data = [
                "status" => $dataReq->status,
                "auto" => $dataReq->auto == "HIDUP" ? "HIDUP" : "MATI",
                "keterangan" => $dataReq->keterangan,
                "perangkat_id" => $dataReq->perangkat_id,
            ];

            $pompa = Pompa::where('id', $id)->update($data);

            if ($pompa == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $pompa
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pompa = Pompa::where("id", $id)->delete();

            if ($pompa != null)
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
                "message" => $th->getMessage(),
            ]);
        }
    }
}
