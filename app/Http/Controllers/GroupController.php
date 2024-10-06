<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Libraries\System;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAll()
    {
        $users = Group::all();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $users
        ]);
    }
}