<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommentModel;
use App\Models\RateModel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {

        $hasRated = RateModel::where('carId', $request->carId)
            ->where('userId', $request->userId)
            ->exists();

        if (!$hasRated) {
            return response()->json(['error' => 'You must rate this car before commenting.'], 403); // 403 Forbidden
        }
        $comment = new CommentModel;
        $comment->carId = $request->carId;
        $comment->userId = $request->userId;
        $comment->comment = $request->comment;

        if ($comment->save()) {
            return response()->json($comment, 200);
        }

        return response()->json(['error' => 'Failed to save comment'], 500);
    }
}
