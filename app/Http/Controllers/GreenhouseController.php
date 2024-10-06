<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\GreenHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GreenhouseController extends Controller
{
    public function findOne($id)
    {
        try {
            $data = GreenHouse::with('jenis_tanaman')->where('id', $id)->first();

            return response()->json([
                'statusCode' => 200,
                'message' => 'Fetch one successful',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function findAll()
    {
        try {
            $data = GreenHouse::with('jenis_tanaman')->get();

            return System::response(200, [
                'statusCode' => 200,
                'message' => 'Fetch all successful',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'ukuran' => 'required',
            'pemilik' => 'required',
            'gambar' => 'required',
            'pengelola' => 'required',
            'jenis_tanaman_id' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validated->fails())
            return System::badRequest($validated);


        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = $request->gambar;
            $file->move(public_path('uploads/greenhouse'), $filename);

            $data = [
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'ukuran' => $request->ukuran,
                'pemilik' => $request->pemilik,
                'gambar' => $request->gambar,
                'pengelola' => $request->pengelola,
                'telegram_id' => $request->telegram_id,
                'jenis_tanaman_id' => $request->jenis_tanaman_id,
                'latitude' => null,
                'longitude' => null
            ];

            try {
                GreenHouse::create($data);
                return System::response(201, [
                    'statusCode' => 201,
                    'message' => 'Create data successful'
                ]);
            } catch (\Throwable $th) {
                return System::response(500, [
                    'statusCode' => 500,
                    'message' => $th->getMessage()
                ]);
            }
        }
    }
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'ukuran' => 'required',
            'pemilik' => 'required',
            'gambar' => 'required',
            'pengelola' => 'required',
            'jenis_tanaman_id' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validated->fails())
            return System::badRequest($validated);


        $dataLama = GreenHouse::where('id', $id)->first();

        if ($dataLama->gambar != $request->gambar) {
            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (File::exists(public_path('uploads/greenhouse/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/greenhouse/' . $dataLama->gambar));
            }

            // Upload gambar baru
            $file = $request->file('image');
            $filename = $request->gambar; // Anda mungkin perlu menyesuaikan ini
            $file->move(public_path('uploads/greenhouse'), $filename);
        }

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'ukuran' => $request->ukuran,
            'pemilik' => $request->pemilik,
            'gambar' => $request->gambar,
            'pengelola' => $request->pengelola,
            'telegram_id' => $request->telegram_id,
            'jenis_tanaman_id' => $request->jenis_tanaman_id,
            'latitude' => null,
            'longitude' => null
        ];

        try {
            $greenhouse = GreenHouse::where('id', $id)->update($data);

            if ($greenhouse == 0)
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
                'message' => $th
            ]);
        }
    }
    public function updateLatLong(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validated->fails())
            return System::badRequest($validated);

        $dataReq = (object) $request->input();

        $data = [
            "latitude" => $dataReq->latitude,
            "longitude" => $dataReq->longitude
        ];

        try {
            $greenhouse = GreenHouse::where('id', $id)->update($data);

            if ($greenhouse == 0)
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
            $dataLama = GreenHouse::where('id', $id)->first();

            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (File::exists(public_path('uploads/greenhouse/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/greenhouse/' . $dataLama->gambar));
            }

            $greenhouse = GreenHouse::where('id', $id)->delete();

            if ($greenhouse != null)
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
