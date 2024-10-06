<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $penyakit = Penyakit::all();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $penyakit
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
            $penyakit = Penyakit::where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $penyakit
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
            "kode" => "required",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "nama" => $dataReq->nama,
            "kode" => $dataReq->kode
        ];

        try {
            $penyakit = Penyakit::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "data" => $penyakit
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
            "kode" => "required",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "nama" => $dataReq->nama,
            "kode" => $dataReq->kode
        ];

        try {
            $penyakit = Penyakit::where('id', $id)->update($data);

            if ($penyakit == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $penyakit
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
            $penyakit = Penyakit::where("id", $id)->delete();
            if ($penyakit != null)
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