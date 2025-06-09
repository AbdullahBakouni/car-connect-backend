<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LikeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class LikesController extends Controller
{
    public function addLike(Request $request)
    {
        try {
            $like = LikeModel::where('carId', $request->carId)
                ->where('userId', $request->userId)
                ->first();

            if ($like) {
                $like->delete();
                return response()->json(['message' => 'Like removed'], 200);
            } else {
                $like = new LikeModel();
                $like->carId = $request->carId;
                $like->userId = $request->userId;
                $like->save();

                return response()->json(['message' => 'Like added'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
