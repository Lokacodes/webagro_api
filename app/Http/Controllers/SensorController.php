<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\GreenHouse;
use App\Models\Kontrol;
use App\Models\Notifikasi;
use App\Models\Pompa;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll(Request $request)
    {
        $greenhouse = $request->input('greenhouse_id');

        try {
            $sensor = Sensor::with("perangkat")
                ->whereHas('perangkat', function ($q) use ($greenhouse) {
                    $q->where('greenhouse_id', $greenhouse);
                })
                ->orderBy('created_at', 'DESC')
                ->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "fetch data successfuly",
                "data" => $sensor
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function findDataSensorTerbaru($greenhouseId)
    {
        $greenhouse = GreenHouse::where('id', $greenhouseId)
            ->first();

        $sensor = Sensor::with("perangkat", "perangkat.sensor")
            ->whereHas('perangkat', function ($q) use ($greenhouseId) {
                $q->where('greenhouse_id', $greenhouseId);
            })
            ->orderBy("created_at", "DESC")
            ->select([
                "sensor.*"
            ])
            ->first();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'data sensor terbaru',
            'data' => [
                "sensor" => $sensor,
                "greenhouse" => $greenhouse
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'perangkat_id' => 'required| numeric',
            'sensor_suhu' => 'required | numeric',
            'sensor_kelembaban' => 'required|numeric',
            'sensor_ldr' => 'required|numeric',
            'sensor_tds' => 'required|numeric',
            'sensor_waterflow' => 'required|numeric',
            'sensor_volume' => 'nullable|numeric',
        ]);

        if ($validated->fails()) {
            return System::badRequest($validated);
        }

        $perangkatId = $request->input('perangkat_id');
        $suhu = $request->input('sensor_suhu');
        $kelembaban = $request->input('sensor_kelembaban');
        $ldr = $request->input('sensor_ldr');
        $tds = $request->input('sensor_tds');
        $wtf = $request->input('sensor_waterflow');
        $vlm = $request->input('sensor_volume');

        // Set Sensor
        $setting = Kontrol::with('perangkat.greenhouse')
            ->where('perangkat_id', $perangkatId)
            ->first();

        // Pompa Auto
        $pompa = Pompa::where('perangkat_id', $perangkatId)
            ->first();

        // Clearing
        $this->clearingDataSensor($perangkatId);

        try {
            Sensor::create([
                'sensor_suhu' => $suhu,
                'sensor_kelembaban' => $kelembaban,
                'sensor_ldr' => $ldr,
                'sensor_tds' => $tds,
                'sensor_waterflow' => $wtf,
                'sensor_volume' => $vlm ?: 0,
                'perangkat_id' => $perangkatId,
            ]);

            // Notifikasi Suhu
            if ($suhu < $setting->suhu_min) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} suhu terdeteksi kurang dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "Suhu terdeteksi kurang dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => 'warning'
                ]);
            }

            if ($suhu > $setting->suhu_max) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} suhu terdeteksi lebih dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "Suhu terdeteksi lebih dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => 'warning'
                ]);
            }

            // Notifikasi TDS
            if ($suhu < $setting->tds_min) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} TDS terdeteksi kurang dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "TDS terdeteksi kurang dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => 'danger'
                ]);
            }

            if ($suhu > $setting->tds_max) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} TDS terdeteksi lebih dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "TDS terdeteksi lebih dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => 'danger'
                ]);
            }

            // Notifikasi Kelembaban
            if ($kelembaban < $setting->kelembaban_min) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} kelembaban terdeteksi kurang dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "Kelembaban terdeteksi kurang dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => "info"
                ]);
            }

            if ($kelembaban > $setting->kelembaban_max) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} kelembaban terdeteksi lebih dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "Kelembaban terdeteksi lebih dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => "info"
                ]);
            }

            // Notifikasi Volume
            if ($vlm < $setting->volume_min) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} volume air terdeteksi kurang dari ketentuan"
                );

                Notifikasi::create([
                    "keterangan" => "Volume air terdeteksi kurang dari ketentuan",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => "secondary"
                ]);
            }

            if ($vlm >= $setting->volume_max) {
                BotController::send(
                    $setting->perangkat->greenhouse->telegram_id,
                    "{$setting->perangkat->greenhouse->nama} volume air sudah mencapai maksimal"
                );

                Notifikasi::create([
                    "keterangan" => "Volume air sudah mencapai maksimal",
                    "status" => "BELUM TERBACA",
                    "perangkat_id" => $perangkatId,
                    "color" => "secondary"
                ]);
            }

            // Pompa Auto
            $status = $vlm < $setting->volume_min ? 'HIDUP' : ($vlm >= $setting->volume_max ? 'MATI' : null);
            if ($pompa->auto == 'HIDUP' && $status != null) {
                Pompa::where('perangkat_id', $perangkatId)
                    ->update([
                        'status' => $status
                    ]);
            }

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Save data successful'
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getChartData($greenhouseId)
    {
        $dataSensor = Sensor::join('perangkat as pe', 'sensor.perangkat_id', '=', 'pe.id')
            ->where('pe.greenhouse_id', $greenhouseId)
            ->limit(10)
            ->select([
                'sensor.*'
            ])
            ->get()
            ->toArray();

        if (!count($dataSensor)) {
            return System::response(200, [
                'statusCode' => 200,
                'message' => 'grafik data',
                'data' => [
                    [
                        "name" => "Grafik Suhu",
                        "labels" => [],
                        "data" => []
                    ],
                    [
                        "name" => "Grafik Kelembaban",
                        "labels" => [],
                        "data" => []
                    ],
                    [
                        "name" => "Grafik Debit Air",
                        "labels" => [],
                        "data" => []
                    ],
                    [
                        "name" => "Grafik TDS",
                        "labels" => [],
                        "data" => []
                    ]
                ]
            ]);
        }

        ksort($dataSensor);

        $labels = [];
        $dataSuhu = [];
        $dataKelembaban = [];
        $dataDebitAir = [];
        $dataTds = [];
        foreach ($dataSensor as $key => $val) {
            $labels[] = date('H:i:s', strtotime($val['created_at']));

            // Data Chart
            $dataSuhu[] = $val['sensor_suhu'];
            $dataKelembaban[] = $val['sensor_kelembaban'];
            $dataDebitAir[] = $val['sensor_waterflow'];
            $dataTds[] = $val['sensor_tds'];
        }

        $chartGrafik = [
            [
                "name" => "Grafik Suhu",
                "labels" => $labels,
                "data" => $dataSuhu
            ],
            [
                "name" => "Grafik Kelembaban",
                "labels" => $labels,
                "data" => $dataKelembaban
            ],
            [
                "name" => "Grafik Debit Air",
                "labels" => $labels,
                "data" => $dataDebitAir
            ],
            [
                "name" => "Grafik TDS",
                "labels" => $labels,
                "data" => $dataTds
            ]
        ];

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'grafik data',
            'data' => $chartGrafik
        ]);
    }

    private function clearingDataSensor($perangkatId)
    {
        $limitMax = 100;
        $dataLiving = 99;

        $count = Sensor::where('perangkat_id', $perangkatId)
            ->count();

        if (!($count > $limitMax))
            return 0;

        Sensor::where('perangkat_id', $perangkatId)
            ->orderBy('created_at', 'ASC')
            ->take($count - $dataLiving)
            ->delete();
    }
}