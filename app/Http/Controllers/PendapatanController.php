<?php

namespace App\Http\Controllers;

use App\Exports\PendapatanExport;
use App\Http\Libraries\System;
use App\Models\Pendapatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PendapatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $pendapatan = Pendapatan::with(['panen', 'greenhouse', 'satuan'])->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'fetch data successfuly',
                'data' => $pendapatan
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function findOne($id)
    {
        try {
            $pendapatan = Pendapatan::with(['panen', 'greenhouse', 'satuan'])->where('id', $id)->first();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'fetch data successfuly',
                'data' => $pendapatan
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'produk' => 'required',
            'catatan' => 'required',
            'kategori' => 'required',
            'nama_pembeli' => 'required',
            'alamat_pembeli' => 'required',
            'jumlah' => 'required | numeric',
            'nominal' => 'required | numeric',
            'panen_id' => 'required | numeric',
            'greenhouse_id' => 'required | numeric'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->all();

            $data = [
                'tanggal' => $dataReq->tanggal,
                'produk' => $dataReq->produk,
                'catatan' => $dataReq->catatan,
                'kategori' => $dataReq->kategori,
                'nama_pembeli' => $dataReq->nama_pembeli,
                'alamat_pembeli' => $dataReq->alamat_pembeli,
                'jumlah' => $dataReq->jumlah,
                'nominal' => $dataReq->nominal,
                'panen_id' => $dataReq->panen_id,
                'greenhouse_id' => $dataReq->greenhouse_id,
                'satuan_id' => $dataReq->satuan_id
            ];

            $pendapatan = Pendapatan::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data successfuly',
                'data' => $pendapatan
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
            'tanggal' => 'required',
            'produk' => 'required',
            'catatan' => 'required',
            'kategori' => 'required',
            'nama_pembeli' => 'required',
            'alamat_pembeli' => 'required',
            'jumlah' => 'required | numeric',
            'nominal' => 'required | numeric',
            'panen_id' => 'required | numeric',
            'greenhouse_id' => 'required | numeric'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->all();

            $data = [
                'tanggal' => $dataReq->tanggal,
                'produk' => $dataReq->produk,
                'catatan' => $dataReq->catatan,
                'kategori' => $dataReq->kategori,
                'nama_pembeli' => $dataReq->nama_pembeli,
                'alamat_pembeli' => $dataReq->alamat_pembeli,
                'jumlah' => $dataReq->jumlah,
                'nominal' => $dataReq->nominal,
                'panen_id' => $dataReq->panen_id,
                'greenhouse_id' => $dataReq->greenhouse_id,
                'satuan_id' => $dataReq->satuan_id
            ];

            $pendapatan = Pendapatan::where('id', $id)->update($data);

            if ($pendapatan == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Update data successfuly',
                'data' => $pendapatan
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
            $pendapatan = Pendapatan::where('id', $id)->delete();

            if ($pendapatan != null)
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

    public function exportExcel(Request $request)
    {
        $greenhouse_id = $request->get('greenhouse_id');
        $tanggal = $request->get('tanggal');
        $panen_id = $request->get('panen_id');

        $export = new PendapatanExport([
            'greenhouse_id' => $greenhouse_id,
            'tanggal' => $tanggal,
            'panen_id' => $panen_id,
        ]);

        $tanggalFormat = '';

        if (!empty($tanggal)) {
            // Ubah string tanggal menjadi objek Carbon jika belum objek Carbon
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggal);

            // Mengubah format tanggal menjadi dd-mm-yyyy
            $tanggalFormat = $tanggal->format('d-m-Y');
        }

        return Excel::download($export, 'Pendapatan-' . ($tanggalFormat ? $tanggalFormat : 'SemuaTanggal') . '-' . ($panen_id ? $panen_id : 'TanpaPanen') . '.xlsx');
    }
}