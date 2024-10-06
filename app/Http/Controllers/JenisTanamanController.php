<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\JenisTanaman;
use Illuminate\Http\Request;

class JenisTanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        $jenis_tanaman = JenisTanaman::all();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $jenis_tanaman
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Create a new JenisTanaman
        $jenisTanaman = JenisTanaman::create([
            'nama' => $validated['nama'],
        ]);

        // Return a response (JSON or redirect)
        return response()->json([
            'message' => 'Jenis Tanaman created successfully!',
            'data' => $jenisTanaman
        ], 201);
    }
}
