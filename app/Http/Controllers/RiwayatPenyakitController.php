<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\CFPengguna;
use App\Models\Diagnosa;
use App\Models\GreenHouse;
use App\Models\Pengetahuan;
use App\Models\Penyakit;
use App\Models\Riwayat;
use App\Models\RiwayatPenyakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RiwayatPenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $riwayat = RiwayatPenyakit::with(['penyakit', 'diagnosa'])->get();

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

    public function penyakitGreenhouse()
    {
        try {
            $greenhouses = GreenHouse::select('id', 'nama')->get();
            $data = [];
            $dataGreenhouse_id = [];

            foreach ($greenhouses as $greenhouse) {
                $riwayat = RiwayatPenyakit::with(['diagnosa', 'penyakit'])
                    ->whereHas('diagnosa', function ($query) use ($greenhouse) {
                        $query->where('greenhouse_id', $greenhouse->id);
                    })
                    ->get();

                // Simpan data untuk setiap greenhouse dalam array $data dengan nama greenhouse sebagai kunci
                $data[$greenhouse->nama][] = $riwayat;
            }

            $sortedData = [];

            foreach ($data as $greenhouseName => $greenhouseRiwayat) {
                foreach ($greenhouseRiwayat as $riwayat) {
                    foreach ($riwayat as $item) {
                        $penyakit_id = $item['penyakit_id'];
                        $namaPenyakit = $item['penyakit']['nama'];
                        $greenhouse_id = $item['diagnosa']['greenhouse_id'];
                        $created_at = $item['created_at']; // Menambahkan created_at


                        if (!isset($sortedData[$greenhouseName][$penyakit_id])) {
                            $sortedData[$greenhouseName][$penyakit_id] = [
                                'nama_penyakit' => $namaPenyakit,
                                'jumlah_data' => 1,
                                'total_jumlah' => $item['jumlah'],
                                'created_at' => $created_at, // Menambahkan created_at
                            ];
                        } else {
                            $sortedData[$greenhouseName][$penyakit_id]['jumlah_data']++;
                            $sortedData[$greenhouseName][$penyakit_id]['total_jumlah'] += $item['jumlah'];
                        }
                        $dataGreenhouse_id[$greenhouseName] = $greenhouse_id;
                    }
                }
            }

            $sortDataGreenhouse_id = [];
            foreach ($dataGreenhouse_id as $val) {
                if (!is_array($val)) {
                    if (empty($sortDataGreenhouse_id) || !in_array($val, $sortDataGreenhouse_id)) {
                        $sortDataGreenhouse_id[] = $val;
                    }
                }
            }



            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfully!",
                "data" => $sortedData,
                'dataGreenhouse_id' => $dataGreenhouse_id
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function getPenyakitStatistics()
    {
        try {
            // Ambil data penyakit dan hitung jumlahnya berdasarkan penyakit_id di diagnosa
            $statistics = RiwayatPenyakit::selectRaw('penyakit_id, COUNT(*) as jumlah')
                ->where('nilai', '>', '0.7')
                ->groupBy('penyakit_id')
                ->with('penyakit') // Assuming you have a relationship defined in RiwayatPenyakit model
                ->get()
                ->map(function ($item) {
                    if ($item->penyakit) {
                        $item->penyakit_name = $item->penyakit->name; // or any field you need
                    } else {
                        $item->penyakit_name = 'Unknown';
                    }
                    return $item;
                });

            $dataStat = [
                'nama' => [],
                'nilai' => []
            ];

            foreach ($statistics as $val) {
                $dataStat['nama'][] = $val->penyakit->nama;
                $dataStat['nilai'][] = $val->jumlah;
            }

            return response()->json([
                "statusCode" => 200,
                "message" => "Fetch data successfully!",
                "data" => $dataStat
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "statusCode" => 500,
                "message" => $th->getMessage()
            ], 500);
        }
    }


    public function findAllById($id)
    {
        try {
            $riwayat = RiwayatPenyakit::with(['penyakit.post', 'diagnosa'])->orderBy('nilai', 'DESC')->where('diagnosa_id', $id)->get();

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

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        $riwayat = Riwayat::where('diagnosa_id', $id)->get();

        $dataPenyakit = [];
        $dataPengetahuan = [];
        $cfGejala = [];

        foreach ($riwayat as $val) {
            $penyakitList = Penyakit::whereHas('pengetahuan', function ($q) use ($val) {
                $q->where('gejala_id', $val->gejala_id);
            })->get();

            foreach ($penyakitList as $penyakit) {
                if (!isset($dataPenyakit[$penyakit->id])) {
                    $dataPenyakit[$penyakit->id] = [
                        'id' => $penyakit->id,
                        'nama' => $penyakit->nama,
                        'kode' => $penyakit->kode,
                        'created_at' => $penyakit->created_at,
                        'updated_at' => $penyakit->updated_at,
                        'deleted_at' => $penyakit->deleted_at,
                        'riwayat' => [],
                    ];
                }
                $dataPenyakit[$penyakit->id]['riwayat'][] = $val;
            }

            $pengetahuan = Pengetahuan::where('gejala_id', $val->gejala_id)->get();

            foreach ($pengetahuan as $v) {
                $dataPengetahuan[$v->gejala_id] = [
                    'gejala_id' => $v->gejala_id,
                    'CF_pakar' => round($v->mb * $v->md, 3)
                ];
            }
        }

        // Mengubah array asosiatif menjadi array indeks numerik
        $dataPenyakit = array_values($dataPenyakit);

        foreach ($dataPenyakit as $val) {
            foreach ($val['riwayat'] as $v) {
                $cf = CFPengguna::where('id', $v['cf_id'])->first();
                // Cek apakah gejala_id ada di dataPengetahuan
                if (isset($dataPengetahuan[$v->gejala_id])) {
                    $cfGejala[$val['id']][][] = round($dataPengetahuan[$v->gejala_id]['CF_pakar'] * $cf->nilai, 3);
                }
            }
        }

        $cfHasil = [];
        foreach ($dataPenyakit as $val) {
            $cfOld = [];
            $cfNilai = 0;
            foreach ($val['riwayat'] as $i => $v) {
                if (count($val['riwayat']) > 1) {
                    if (isset($cfGejala[$val['id']][($i + 1)][0])) {
                        if ($i == 0) {
                            $cfOld[($i + 1)] = $cfGejala[$val['id']][$i][0] + $cfGejala[$val['id']][($i + 1)][0] * (1 - $cfGejala[$val['id']][$i][0]);
                        } else {
                            $cfOld[($i + 1)] = $cfOld[$i] + $cfGejala[$val['id']][($i + 1)][0] * (1 - $cfOld[$i]);
                        }
                    } else {
                        $cfNilai = $cfOld[$i];
                    }
                }
            }
            $cfHasil[] = [
                'penyakit_id' => $val['id'],
                'nilai' => round($cfNilai, 3),
                'bobot' => round($cfNilai, 3) * 100 . '%',
                'diagnosa_id' => $id,
            ];
        }


        try {
            $riwayat = RiwayatPenyakit::insert($cfHasil);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successfuly",
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "bobot" => "required",
            "nilai" => "required",
            "penyakit_id" => "required | numeric",
            "diagnosa_id" => "required | numeric",
            "percobaan" => "required"
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataReq = (object) $request->input();

        $data = [
            "penyakit_id" => $dataReq->penyakit_id,
            "diagnosa_id" => $dataReq->diagnosa_id,
            "bobot" => $dataReq->bobot,
            "nilai" => $dataReq->nilai,
            "percobaan" => $dataReq->percobaan
        ];

        try {
            $riwayat = RiwayatPenyakit::where('id', $id)->update($data);

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
            $riwayat = RiwayatPenyakit::where("id", $id)->delete();
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