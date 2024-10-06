<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Karyawan;
use App\Models\Panen;
use App\Models\RekapAbsen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RekapAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $RekapAbsen = RekapAbsen::with(['panen', 'karyawan.jabatan'])->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch data successfuly',
                'data' => $RekapAbsen
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function findAllPanen($id)
    {
        try {
            $RekapAbsen = RekapAbsen::with(['panen', 'karyawan.jabatan'])->where('panen_id', $id)->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch data successfuly',
                'data' => $RekapAbsen
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function findOneKaryawan($id)
    {
        try {
            $RekapAbsen = RekapAbsen::with(['panen', 'karyawan.jabatan'])->where('karyawan_id', $id)->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch data successfuly',
                'data' => $RekapAbsen
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function rekapPanen($id)
    {
        try {
            $panen = Panen::orderBy('id', 'desc')
                ->where('id', '<=', $id)
                ->get();

            $tanggalSatu = Carbon::parse($panen[0]->tanggal_panen)->format('Y-m-d');

            // Pastikan ada setidaknya dua entri di dalam $panen
            if ($panen->count() < 2) {
                // Query berdasarkan ketersediaan tanggal
                $query = Karyawan::withCount([
                    'absensi as jumlah' => function ($query) use ($tanggalSatu) {
                        $query->where('tanggal', '<=', $tanggalSatu);
                        $query->where('status', 'MASUK');
                    }
                ]);

            } else {
                $tanggalDua = Carbon::parse($panen[1]->tanggal_panen)->addDay()->format('Y-m-d');

                $query = Karyawan::withCount([
                    'absensi as jumlah' => function ($query) use ($tanggalSatu, $tanggalDua) {
                        if (isset($tanggalDua)) {
                            $query->whereBetween('tanggal', [$tanggalDua, $tanggalSatu]);
                        }
                        $query->where('status', 'MASUK');
                    }
                ]);
            }

            $karyawans = $query->get();

            $rekap = [];
            foreach ($karyawans as $karyawan) {
                $rekapData[] = [
                    'karyawan_id' => $karyawan->id,
                    'panen_id' => $id,
                    'jumlah' => $karyawan->jumlah,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $rekap = RekapAbsen::insert($rekapData); // Use insert for multiple records

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Fetch data successfuly',
                'data' => $rekap
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $rekap = RekapAbsen::where('id', $id)->delete();
            if ($rekap != null) {
                return System::response(200, [
                    'statusCode' => 200,
                    'message' => 'Delete data succesfuly',
                ]);
            }
            return System::response(400, [
                'statusCode' => 400,
                'message' => 'Delete data gagal, id tidak ditemukan!!',
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
}