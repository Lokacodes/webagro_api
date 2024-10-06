<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        $jabatan = Jabatan::all();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $jabatan
        ]);
    }
}