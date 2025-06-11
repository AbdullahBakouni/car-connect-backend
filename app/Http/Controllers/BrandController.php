<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BrandModel;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function addBrand(Request $request)
    {
        $brand = new BrandModel;
        $brand->name = $request->name;
        $res = $brand->save();
        if ($res) {
            return response()->json($brand, 200);
        }
        return response()->json(['error' => 'can not add brand'], 500);
    }

    public function getBrands()
    {
        $brands =  BrandModel::all();

        if ($brands) {
            return response()->json($brands, 200);
        }
        return response()->json(['error' => 'can not get brands'], 500);
    }
}
