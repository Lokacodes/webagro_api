<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Karyawan;
use Illuminate\Support\Str;
use App\Models\Users;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll(Request $request)
    {
        // Tangkap parameter query 'role'
        $roleParams = $request->query('role');

        // Mulai dengan query dasar
        $query = Users::with('role.group');

        // Tambahkan kondisi jika roleParams disediakan
        if ($roleParams !== null) {
            $query->whereHas('role', function ($query) use ($roleParams) {
                $query->where('nama', $roleParams);
            });
        }

        // Eksekusi query dan ambil data
        $users = $query->get();

        // Susun data yang akan dikembalikan
        $data = [];
        foreach ($users as $user) {
            $dataTemp = [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'role' => $user->role ? $user->role->nama : null,
                'group' => $user->role && $user->role->group ? $user->role->group->nama : null,
            ];
            array_push($data, $dataTemp);
        }

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $data
        ]);
    }

    public function findOne($id)
    {

        $users = Users::with('role.group')->where('id', $id)->first();

        $data = [
            'id' => $users->id,
            'nama' => $users->nama,
            'username' => $users->username,
            'password' => $users->password,
            'role_id' => $users->role_id,
            'group_id' => $users->role->group_id,
            'created_at' => $users->created_at,
            'updated_at' => $users->updated_at,
            'role' => $users->role ? $users->role->nama : null,
            'group' => $users->role && $users->role->group ? $users->role->group->nama : null,
        ];

        if (!$users)
            return System::response(400, [
                'statusCode' => 400,
                'message' => 'Data not found',
            ]);

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch one successful',
            'data' => $data
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        if ($validated->fails())
            return System::badRequest($validated);

        $data = [
            'nama' => $request->input('nama'),
            'username' => $request->input('username'),
            'role_id' => intval($request->input('role_id')),
            'password' => Hash::make($request->input('password')),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            DB::beginTransaction();

            $user = Users::create($data);
            $role_id = $request->input('role_id');

            if (!in_array($role_id, [1, 10, 15])) {
                $jabatan_id = '';

                switch ($role_id) {
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $jabatan_id = 2;
                        break;
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        $jabatan_id = 1;
                        break;
                    case 11:
                    case 12:
                    case 13:
                    case 14:
                        $jabatan_id = 3;
                        break;
                    default:
                        $jabatan_id = '';
                        break;
                }

                $dataKaryawan = [
                    'nama' => $request->input('nama'),
                    'nik' => '',
                    'alamat' => '',
                    'jkel' => 'LAKI-LAKI',
                    'jabatan_id' => $jabatan_id,
                    'user_id' => $user->id,
                    'greenhouse_id' => 1
                ];

                Karyawan::create($dataKaryawan);
            }

            DB::commit();


            return System::response(201, [
                'statusCode' => 201,
                'message' => 'Create data successful'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return System::response(500, [
                'statusCode' => 500,
                'role_id' => $request->input('role_id'),
                'message' => $th
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        if ($validated->fails())
            return System::badRequest($validated);

        $data = [
            'nama' => $request->input('nama'),
            'username' => $request->input('username'),
            'role_id' => $request->input('role_id'),
            'password' => $request->input('password'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $user = Users::where('id', $id)->update($data);

            if ($user == 0)
                return System::response(400, [
                    "statusCode" => 400,
                    "message" => "Update data gagal, id tidak ditemukan!!",
                ]);

            return System::response(200, [
                "statusCode" => 200,
                "message" => "Update data successfuly!!",
                "data" => $user
            ]);
        } catch (\Throwable $th) {
            return System::response(500, [
                'statusCode' => 500,
                'message' => 'Error update data'
            ]);
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        if ($validated->fails())
            return System::badRequest($validated);

        $data = [
            'nama' => $request->input('nama'),
            'username' => $request->input('username'),
            'role_id' => intval($request->input('role_id')),
            'password' => Hash::make($request->input('password')),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            Users::where('id', $id)->update($data);
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
            $user = Users::where('id', $id)->delete();
            if ($user != null)
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
