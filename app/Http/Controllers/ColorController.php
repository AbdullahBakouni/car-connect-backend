<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ColorModel;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function addColor(Request $request)
    {
        $color = new ColorModel;
        $color->name = $request->name;
        $res = $color->save();
        if ($res) {
            return response()->json($color, 200);
        }
        return response()->json(['error' => 'can not add color'], 500);
    }

    public function getColors()
    {
        $brands =  ColorModel::all();

        if ($brands) {
            return response()->json($brands, 200);
        }
        return response()->json(['error' => 'can not get brands'], 500);
    }
}
