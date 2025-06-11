<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ModelModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function addModel(Request $request)
    {
        $model = new ModelModel;
        $model->name = $request->name;
        $res = $model->save();
        if ($res) {
            return response()->json($model, 200);
        }
        return response()->json(['error' => 'can not add model'], 500);
    }

    public function getModels()
    {
        $models =  ModelModel::all();

        if ($models) {
            return response()->json($models, 200);
        }
        return response()->json(['error' => 'can not get models'], 500);
    }
}
