<?php

namespace App\Http\Controllers;

use App\Exports\PanenExport;
use App\Http\Libraries\System;
use App\Models\Modal;
use App\Models\Panen;
use App\Models\Pendapatan;
use App\Models\Pengeluaran;
use App\Models\RekapAbsen;
use App\Models\SDM;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PanenController extends Controller
{
    public function findAll(Request $request)
    {
        try {
            $panen = Panen::with('greenhouse')->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch all successful',
                'data' => $panen
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function findAbsenAll(Request $request)
    {
        try {
            $panen = Panen::with('greenhouse')
                ->whereDoesntHave('rekapAbsen')
                ->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch all successful',
                'data' => $panen
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function findOne($id)
    {
        $panen = Panen::where('id', $id)->first();

        if (!$panen)
            return System::response(400, [
                'statusCode' => 400,
                'message' => 'Data not found',
            ]);

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch one successful',
            'data' => $panen
        ]);
    }

    public function findPanenGreenhouse($id)
    {
        $panen = Panen::where('greenhouse_id', $id)->get();

        if (!$panen)
            return System::response(400, [
                'statusCode' => 400,
                'message' => 'Data not found',
            ]);

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch one successful',
            'data' => $panen
        ]);
    }
    public function findOneLast()
    {
        try {
            $panen = Panen::orderBy('id', 'desc')->first();
            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch one successful',
                'data' => $panen
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th
            ]);
        }
    }

    public function getTahunPanen()
    {
        try {
            $tahunPengeluaranPanen = Pengeluaran::selectRaw('YEAR(tanggal) AS tahun')->distinct()->get();
            $tahunPendapatanPanen = Pendapatan::selectRaw('YEAR(tanggal) AS tahun')->distinct()->get();
            $tahunSDMPanen = SDM::selectRaw('YEAR(tanggal) AS tahun')->distinct()->get();
            $tahunModalPanen = Modal::selectRaw('YEAR(tanggal) AS tahun')->distinct()->get();

            // Mengambil hasil query sebagai array
            $tahunPengeluaranArray = $tahunPengeluaranPanen->pluck('tahun')->toArray();
            $tahunPendapatanArray = $tahunPendapatanPanen->pluck('tahun')->toArray();
            $tahunSDMArray = $tahunSDMPanen->pluck('tahun')->toArray();
            $tahunModalArray = $tahunModalPanen->pluck('tahun')->toArray();

            // Menggabungkan kedua array dan menghilangkan duplikat
            $tahunPanenArray = array_unique(array_merge($tahunPengeluaranArray, $tahunPendapatanArray, $tahunSDMArray, $tahunModalArray));

            // Mengurutkan tahun dalam array
            sort($tahunPanenArray);

            $tahunPanenArray = (object) $tahunPanenArray;
            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch one successful',
                'data' => $tahunPanenArray
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');
        $tanggal = $request->get('tanggal');

        $export = new PanenExport([
            'greenhouse_id' => $greenhouse_id,
            'tanggal' => $tanggal,
        ]);

        $tanggalFormat = '';

        if (!empty($tanggal)) {
            // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggal);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalFormat = $tanggal->format('d-m-Y');
        }

        return Excel::download($export, 'Panen-' . ($tanggalFormat ? $tanggalFormat : 'SemuaTanggal') . '.xlsx');
    }


    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'greenhouse_id' => 'required',
            'tanggal_tanam' => 'required|date',
            'tanggal_panen' => 'required|date',
        ]);

        if ($validated->fails())
            return System::badRequest($validated);

        $data = [
            'nama' => $request->input('nama'),
            'tanggal_tanam' => $request->input('tanggal_tanam'),
            'tanggal_panen' => $request->input('tanggal_panen'),
            'greenhouse_id' => $request->input('greenhouse_id')
        ];

        try {
            Panen::create($data);
            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data successful'
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => 'Error create data'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'greenhouse_id' => 'required',
            'tanggal_tanam' => 'required|date',
            'tanggal_panen' => 'required|date',
        ]);

        if ($validated->fails())
            return System::badRequest($validated);

        $data = [
            'nama' => $request->input('nama'),
            'tanggal_tanam' => $request->input('tanggal_tanam'),
            'tanggal_panen' => $request->input('tanggal_panen'),
            'greenhouse_id' => $request->input('greenhouse_id')
        ];

        try {
            $panen = Panen::where('id', $id)->update($data);

            if ($panen == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Update data successful'
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => 'Error update data'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $panen = Panen::where('id', $id)->delete();

            if ($panen != null)
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
                'message' => 'Error delete data'
            ]);
        }
    }
}