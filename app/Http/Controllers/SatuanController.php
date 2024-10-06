<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        $satuan = Satuan::all();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $satuan
        ]);
    }
}