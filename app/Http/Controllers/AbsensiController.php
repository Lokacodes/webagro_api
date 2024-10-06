<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Http\Libraries\System;
use App\Models\Absensi;
use App\Models\GreenHouse;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll(Request $request)
    {
        try {
            $karyawanId = $request->query("karyawan-id");


            $query = Absensi::with(["karyawan.jabatan", "karyawan.user.role"]);

            if ($karyawanId != null) {
                $query->where("karyawan_id", $karyawanId)
                    ->where(function ($query) {
                        $query->where("status", "MASUK")
                            ->orWhere("status", "SAKIT");
                    });
            }

            $absensi = $query->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch Data successfuly",
                "data" => $absensi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function findToGrafik()
    {
        try {
            // Get the latest 5 dates
            $dates = Absensi::select('tanggal')
                ->distinct()
                ->orderBy('tanggal', 'desc')
                ->take(5)
                ->pluck('tanggal')
                ->toArray();

            // Prepare the data
            $attendanceData = [];
            foreach ($dates as $date) {
                $masuk = Absensi::where('tanggal', $date)->where('status', 'MASUK')->count();
                $izin = Absensi::where('tanggal', $date)->where('status', 'IZIN')->count();
                $absen = Absensi::where('tanggal', $date)->where('status', 'SAKIT')->count();

                $attendanceData[] = [
                    'tanggal' => $date,
                    'MASUK' => $masuk,
                    'IZIN' => $izin,
                    'ABSEN' => $absen,
                ];
            }


            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch Data successfuly",
                "data" => $attendanceData
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }
    public function findOneAbsensi($id)
    {
        try {
            // Mendapatkan tanggal sekarang dalam format yang sesuai
            $currentDate = Carbon::now()->format('Y-m-d');
            $absensi = [];

            $absensi = Absensi::with("karyawan.jabatan")
                ->where('karyawan_id', $id)
                ->whereDate("tanggal", $currentDate)
                ->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch Data successfully",
                "data" => $absensi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }
    public function findAbsensi($id)
    {
        try {
            // Mengambil data absensi berdasarkan ID karyawan dan tanggal absensi
            $absensi = Absensi::with(["karyawan.jabatan", "karyawan.user.role"])
                ->where("karyawan_id", $id)
                ->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch Data successfully",
                "data" => $absensi
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                "statusCode" => 500,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');

        $export = new AbsensiExport([
            'greenhouse_id' => $greenhouse_id,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
        ]);

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $tanggal_awal = Carbon::createFromFormat('Y-m-d', $tanggal_awal);
            $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $tanggal_akhir);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalAwalFormat = $tanggal_awal->format('d-m-Y');
            $tanggalAkhirFormat = $tanggal_akhir->format('d-m-Y');
        }

        // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon

        return Excel::download($export, 'Rekap-Absen-' . (!empty($tanggal_awal) && !empty($tanggal_akhir) ? $tanggalAwalFormat . ' - ' . $tanggalAkhirFormat : 'SemuaTanggal') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "catatan" => "required",
            "karyawan_id" => "required",
            "latitude" => "required",
            "longitude" => "required"
        ]);
        if ($validator->fails())
            return System::badRequest($validator);

        $currentDate = Carbon::now()->format('Y-m-d');

        if ($request->input("status") == "MASUK") {

            $karyawan = Karyawan::where('id', $request->input('karyawan_id'))->first();
            if (!$karyawan) {
                return System::response(404, [
                    "statusCode" => 404,
                    "message" => "Karyawan not found",
                ]);
            }

            $greenhouse = GreenHouse::where('id', $karyawan->greenhouse_id)->first();
            if (!$greenhouse) {
                return System::response(404, [
                    "statusCode" => 404,
                    "message" => "GreenHouse not found",
                ]);
            }

            $latitudeFrom = deg2rad($request->input('latitude'));
            $longitudeFrom = deg2rad($request->input('longitude'));
            $latitudeTo = deg2rad($greenhouse->latitude);
            $longitudeTo = deg2rad($greenhouse->longitude);

            $earthRadius = 6371000; // Earth's radius in meters

            $latDelta = $latitudeTo - $latitudeFrom;
            $lonDelta = $longitudeTo - $longitudeFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latitudeFrom) * cos($latitudeTo) * pow(sin($lonDelta / 2), 2)));
            $distance = $angle * $earthRadius;

            if ($distance > 200) {
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Jara lebih dari 200 M dari titik lokasi",
                ]);
            }
        }


        try {
            $data = [
                "tanggal" => $currentDate,
                "status" => $request->input("status"),
                "catatan" => $request->input("catatan"),
                "karyawan_id" => $request->input("karyawan_id"),
                "latitude" => $request->input("latitude"),
                "longitude" => $request->input("longitude")
            ];

            $absensi = Absensi::create($data);

            return System::response(201, [
                "statusCode" => 201,
                "message" => "Create data successful",
                "data" => $absensi
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
            "tanggal" => "required",
            "status" => "required",
            "catatan" => "required",
            "karyawan_id" => "required",
            "latitude" => "required",
            "longitude" => "required"
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $data = [
                "tanggal" => $request->input("tanggal"),
                "status" => $request->input("status"),
                "catatan" => $request->input("catatan"),
                "latitude" => $request->input("latitude"),
                "longitude" => $request->input("longitude"),
                "karyawan_id" => $request->input("karyawan_id")
            ];

            $absensi = Absensi::where('id', $id)->update($data);

            if ($absensi == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successful",
                "data" => $absensi
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
            $absen = Absensi::where("id", $id)->delete();
            if ($absen != null)
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