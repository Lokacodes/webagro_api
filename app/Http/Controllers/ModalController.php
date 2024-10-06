<?php

namespace App\Http\Controllers;

use App\Exports\ModalExport;
use App\Http\Libraries\System;
use App\Models\Modal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {

        try {
            $modal = Modal::with('panen.greenhouse')->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'fetch data successfuly',
                'data' => $modal
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

        try {
            $modal = Modal::with('panen')->where('id', $id)->first();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'fetch data successfuly',
                'data' => $modal
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

        $export = new ModalExport([
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

        return Excel::download($export, 'Modal-' . ($tanggalFormat ? $tanggalFormat : 'SemuaTanggal') . '-' . ($panen_id ? $panen_id : 'TanpaPanen') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'catatan' => 'required',
            'nominal' => 'required | numeric',
            'panen_id' => 'required | numeric'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->all();

            $data = [
                'tanggal' => $dataReq->tanggal,
                'catatan' => $dataReq->catatan,
                'nominal' => $dataReq->nominal,
                'panen_id' => $dataReq->panen_id
            ];

            $modal = Modal::create($data);

            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data successfuly',
                'data' => $modal
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
            'catatan' => 'required',
            'nominal' => 'required | numeric',
            'panen_id' => 'required | numeric'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        try {
            $dataReq = (object) $request->all();

            $data = [
                'tanggal' => $dataReq->tanggal,
                'catatan' => $dataReq->catatan,
                'nominal' => $dataReq->nominal,
                'panen_id' => $dataReq->panen_id
            ];

            $modal = Modal::where('id', $id)->update($data);

            if ($modal == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Update data successfuly'
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
            $modal = Modal::where('id', $id)->delete();
            if ($modal != null)
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
}