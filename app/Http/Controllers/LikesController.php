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
        Log::info('Toggle like request received', [
            'carId' => $request->carId,
            'userId' => $request->userId,
        ]);

        try {
            $like = LikeModel::where('carId', $request->carId)
                ->where('userId', $request->userId)
                ->first();

            if ($like) {
                Log::info('Like found, removing like', ['likeId' => $like->id]);
                $like->delete();

                return response()->json(['message' => 'Like removed'], 200);
            } else {
                Log::info('No like found, adding new like');

                $like = new LikeModel();
                $like->carId = $request->carId;
                $like->userId = $request->userId;
                $like->save();

                return response()->json(['message' => 'Like added'], 200);
            }
        } catch (Exception $e) {
            Log::error('Error toggling like', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
