<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RateModel;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function rateCar(Request $request)
    {
        $existingRate = RateModel::where('carId', $request->carId)
            ->where('userId', $request->userId)
            ->first();

        if ($existingRate) {
            return response()->json(['error' => 'you rated this car before'], 409); // 409 Conflict
        }

        $rate = new RateModel;
        $rate->carId = $request->carId;
        $rate->userId = $request->userId;
        $rate->value = $request->value;

        if ($rate->save()) {
            return response()->json($rate, 200);
        }

        return response()->json(['error' => 'Failed to save rating'], 500);
    }
}
