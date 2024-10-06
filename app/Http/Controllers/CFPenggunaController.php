<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\CFPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CFPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $cf = CFPengguna::orderBy('id', 'DESC')->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $cf
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
            "kondisi" => "required",
            "nilai" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "kondisi" => $dataReq->kondisi,
            "nilai" => $dataReq->nilai
        ];

        try {
            $cf = CFPengguna::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "data" => $cf
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
            "kondisi" => "required",
            "nilai" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "kondisi" => $dataReq->kondisi,
            "nilai" => $dataReq->nilai
        ];

        try {
            $cf = CFPengguna::where('id', $id)->update($data);

            if ($cf == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly",
                "data" => $cf
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
            $cf = CFPengguna::where("id", $id)->delete();

            if ($cf != null)
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