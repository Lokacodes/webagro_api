<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        try {
            $post = Post::with('penyakit')->get();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $post
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
            $post = Post::with('penyakit')->where('id', $id)->first();

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Fetch data successfuly!",
                "data" => $post
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
        $validator = Validator::make($request->all(), [
            "nama" => "required",
            "detail" => "required",
            "saran" => "required",
            "gambar" => "required",
            "penyakit_id" => "required | numeric"
        ]);

        if ($validator->fails())
            return System::badRequest($validator);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->gambar;
            $file->move(public_path('uploads/post'), $filename);

            $data = [
                "nama" => $request->nama,
                "detail" => $request->detail,
                "saran" => $request->saran,
                "gambar" => $request->gambar,
                "penyakit_id" => $request->penyakit_id
            ];
            try {
                $post = Post::create($data);

                return System::response(201, [
                    "statusCode" => 201,
                    "message" => "Create data successfuly",
                    "data" => $post
                ]);
            } catch (\Throwable $th) {
                return System::response(500, [
                    "statusCode" => 500,
                    "message" => $th->getMessage()
                ]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            "nama" => "required",
            "detail" => "required",
            "saran" => "required",
            "gambar" => "required",
            "penyakit_id" => "required | numeric",
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails())
            return System::badRequest($validator);

        $dataLama = Post::where('id', $id)->first();

        if ($dataLama->gambar != $request->gambar) {
            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (File::exists(public_path('uploads/post/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/post/' . $dataLama->gambar));
            }

            // Upload gambar baru
            $file = $request->file('image');
            $filename = $request->gambar; // Anda mungkin perlu menyesuaikan ini
            $file->move(public_path('uploads/post'), $filename);
        }


        $data = [
            "nama" => $request->nama,
            "detail" => $request->detail,
            "saran" => $request->saran,
            "gambar" => $request->gambar,
            "penyakit_id" => $request->penyakit_id
        ];

        try {
            $post = Post::where('id', $id)->update($data);

            if ($post == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!"
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $post
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

            $dataLama = Post::where('id', $id)->first();

            // Menghapus gambar lama jika berbeda dengan gambar baru
            if (File::exists(public_path('uploads/post/' . $dataLama->gambar))) {
                File::delete(public_path('uploads/post/' . $dataLama->gambar));
            }
            $post = Post::where("id", $id)->delete();
            if ($post != null)
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