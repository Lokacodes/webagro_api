<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Libraries\System;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findOne(Request $request, $id)
    {
        $role = Role::where('group_id', $id)->get();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'Fetch all successful',
            'data' => $role
        ]);
    }
}