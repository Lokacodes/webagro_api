<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        $jenis = Jenis::all();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $jenis
        ]);
    }
}