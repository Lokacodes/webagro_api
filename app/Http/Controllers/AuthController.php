<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Libraries\System;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // $validatedData = Validator::make($request->all(), [
        //     'name' => 'required|max:60|min:5',
        //     'email' => 'required|unique:users|email',
        //     'password' => 'required|max:16|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        // ]);
        $data = $request->all();

        $validatedData = Validator::make($data, [
            'username' => 'required|max:60|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:16',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'nama' => 'required|max:100',
            'no_hp' => 'nullable|max:15',
            'foto' => 'nullable|string',
            'role_id' => 'required|exists:role,id'
        ]);

        if ($validatedData->fails()) {
            return response(['errors' => $validatedData->errors()], 422);
        }

        $data = $request->all();

        $data = $validatedData->validated();

        $data['password'] = Hash::make($request->password);

        $user = Users::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(
            [
                'user' => $user,
                'access_token' => $accessToken
            ],
            200
        );
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Attempt to authenticate the user with the given credentials
        if (!Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            return System::response(401, [
                'statusCode' => 401,
                'message' => 'Username atau password salah',
            ]);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Create a new personal access token for the user
        $token = $user->createToken('authToken')->accessToken;

        $data = [
            'id' => $user->id,
            'nama' => $user->nama,
            'username' => $user->username,
            'role_id' => $user->role_id,
            'role' => $user->role ? $user->role->nama : null,
            'group' => $user->role && $user->role->group ? $user->role->group->nama : null,
        ];

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Login successful',
            'data' => $data,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        if ($user) {
            // Revoke the access token
            $user->tokens()->delete(); // This will delete all tokens for the user
            // Or if you want to revoke a specific token, you can do that as well
            // $request->user()->currentAccessToken()->delete();

            return response()->json([
                'statusCode' => 200,
                'message' => 'Logout successful',
            ]);
        }

        return response()->json([
            'statusCode' => 401,
            'message' => 'User not authenticated',
        ], 401);
    }
}
