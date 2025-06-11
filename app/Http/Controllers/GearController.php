<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GearModel;
use Illuminate\Http\Request;

class GearController extends Controller
{
    public function addGear(Request $request)
    {
        $gear = new GearModel;
        $gear->name = $request->name;
        $res = $gear->save();
        if ($res) {
            return response()->json($gear, 200);
        }
        return response()->json(['error' => 'can not add gear'], 500);
    }

    public function getGears()
    {
        $gears =  GearModel::all();

        if ($gears) {
            return response()->json($gears, 200);
        }
        return response()->json(['error' => 'can not get gears'], 500);
    }
}
