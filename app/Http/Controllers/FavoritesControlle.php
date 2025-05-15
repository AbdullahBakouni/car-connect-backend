<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\FavoriteModel;
use App\Models\ImageModel;
use Illuminate\Http\Request;

class FavoritesControlle extends Controller
{
    public function addFavorite(Request $request)
    {

        $exists = FavoriteModel::where('carId', $request->carId)
            ->where('userId', $request->userId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already added to favorites'], 409); // 409 Conflict
        }

        $favorite = new FavoriteModel;
        $favorite->carId = $request->carId;
        $favorite->userId = $request->userId;

        $favorite = $favorite->save();
        if ($favorite) {
            return response()->json([], 200);
        }

        return response()->json([], 500);
    }

    public function getUserFavorites(Request $request)
    {
        $favorites = FavoriteModel::where('userId', $request->userId)
            ->with('car')
            ->get();
        $message = [];
        for ($i = 0; $i < count($favorites); $i++) {
            $images = ImageModel::where('carId', $favorites[$i]->carId)->get();

            array_push($message, [
                'car' => CarModel::where('id', $favorites[$i]->carId)->first(),
                'images' => $images
            ]);
        }

        return response()->json([
            "cars" => $message
        ], 200);
    }
}
