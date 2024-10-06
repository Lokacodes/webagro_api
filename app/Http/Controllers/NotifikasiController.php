<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $notifikasi = Notifikasi::with('perangkat')->get();
            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch Data Successfuly',
                'data' => $notifikasi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getNotifikasiDashboard($greenHouseId)
    {
        $notifikasi = Notifikasi::with("perangkat.greenhouse")
            ->whereHas("perangkat.greenhouse", function ($q) use ($greenHouseId) {
                $q->where("id", $greenHouseId);
            })
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch Data Successfuly',
            'data' => $notifikasi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keterangan' => 'required',
            'status' => 'required',
            'perangkat_id' => 'required',
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->all();

            $data = [
                'keterangan' => $dataReq->keterangan,
                'status' => $dataReq->status,
                'perangkat_id' => $dataReq->perangkat_id
            ];

            $notifikasi = Notifikasi::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data Successfuly!',
                'data' => $notifikasi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'keterangan' => 'required',
            'status' => 'required',
            'perangkat_id' => 'required',
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->all();

            $data = [
                'keterangan' => $dataReq->keterangan,
                'status' => $dataReq->status,
                'perangkat_id' => $dataReq->perangkat_id
            ];

            $notifikasi = Notifikasi::where('id', $id)->update($data);

            if ($notifikasi == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Update data Successfuly!',
                'data' => $notifikasi
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
            $notifikasi = Notifikasi::where('id', $id)->delete();
            if ($notifikasi != null)
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
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroyAll($greenHouseId)
    {
        try {
            Notifikasi::with("perangkat.greenhouse")
                ->whereHas("perangkat.greenhouse", function ($q) use ($greenHouseId) {
                    $q->where("id", $greenHouseId);
                })->delete();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Delete data successfuly'
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
}
