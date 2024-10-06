<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Pengetahuan;
use App\Models\Penyakit;
use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RiwayatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $riwayat = Riwayat::with(['cf', 'diagnosa', 'gejala'])->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $riwayat
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
            $riwayat = Riwayat::with(['cf', 'diagnosa', 'gejala'])->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $riwayat
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findRiwayatDiagnosa($id)
    {
        try {
            $riwayatPilihan = Riwayat::with('gejala')->where('diagnosa_id', $id)->where('jenis', "PILIHAN")->get();
            $riwayatTambahan = Riwayat::with('gejala')->where('diagnosa_id', $id)->where('jenis', "TAMBAHAN")->get();
            $gejala_id = Riwayat::select('gejala_id')->where('diagnosa_id', $id)->get();
            $cf_id = Riwayat::select('cf_id')->where('diagnosa_id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "dataPilihan" => $riwayatPilihan,
                "dataTambahan" => $riwayatTambahan,
                "gejala_id" => $gejala_id,
                "cf_id" => $cf_id
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findRiwayatDiagnosaUpdate($id)
    {
        try {
            $riwayat = Riwayat::with(['cf', 'diagnosa', 'gejala'])->where('diagnosa_id', $id)->get();
            $gejala_id = Riwayat::select('gejala_id')->where('diagnosa_id', $id)->get();
            $cf_id = Riwayat::select('cf_id')->where('diagnosa_id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $riwayat,
                "gejala_id" => $gejala_id,
                "cf_id" => $cf_id
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

        $dataReq = $request->input();

        $dataPenyakit = [];
        foreach ($dataReq as $val) {
            $penyakit = Penyakit::select(['id', 'nama'])->whereHas('pengetahuan', function ($q) use ($val) {
                $q->where('gejala_id', $val['gejala_id']);
            })->get();

            foreach ($penyakit as $val) {
                $dataPenyakit[$val->id] = $val;
            }
        }

        $dataPenyakit = array_values($dataPenyakit);

        $dataPengetahuan = [];

        foreach ($dataPenyakit as $val) {
            $pengetahuan = Pengetahuan::where('penyakit_id', $val->id)->get();

            foreach ($pengetahuan as $v) {
                $hasGejala = false;

                foreach ($dataReq as $value) {
                    if ($v->gejala()->where('id', $value['gejala_id'])->exists()) {
                        $hasGejala = true;
                        break;
                    }
                }

                if (!$hasGejala) {
                    $dataPengetahuan[$v->id] = $v;
                }
            }
        }


        $dataPengetahuan = array_values($dataPengetahuan);

        foreach ($dataReq as $val) {
            $dataRiwayatPilihan[] = [
                "cf_id" => $val['cf_id'],
                "diagnosa_id" => $val['diagnosa_id'],
                "gejala_id" => $val['gejala_id'],
                "jenis" => "PILIHAN"
            ];
        }

        foreach ($dataPengetahuan as $val) {
            $dataRiwayatTambahan[] = [
                "cf_id" => null,
                "diagnosa_id" => $dataReq[0]['diagnosa_id'],
                "gejala_id" => $val->gejala_id,
                "jenis" => "TAMBAHAN"
            ];
        }

        try {
            $riwayatPilihan = Riwayat::insert($dataRiwayatPilihan);
            $riwayatTambahan = Riwayat::insert($dataRiwayatTambahan);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
                "dataPilihan" => $riwayatPilihan,
                "dataTambahan" => $riwayatTambahan
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
            "cf_id" => "required | numeric",
            "diagnosa_id" => "required | numeric",
            "gejala_id" => "required | numeric",
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "cf_id" => $dataReq->cf_id,
            "diagnosa_id" => $dataReq->diagnosa_id,
            "gejala_id" => $dataReq->gejala_id
        ];

        try {
            $riwayat = Riwayat::where('id', $id)->update($data);

            if ($riwayat == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $riwayat
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
            $riwayat = Riwayat::where("id", $id)->delete();
            if ($riwayat != null)
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