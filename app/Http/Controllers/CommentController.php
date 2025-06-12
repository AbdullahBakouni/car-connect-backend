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

    public function getComment(Request $request){
              if (!$request->has('carId')) {
        return response()->json(['error' => 'Car ID is required'], 400); // 400 Bad Request
    }
        $comments = CommentModel::where('carId', $request->carId)
            ->with('user:id,phone')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                return [
                    'user-phone' => $comment->user->phone ?? '0000000000',
                    'comment' => $comment->comment,
                    'date' => $comment->created_at->toDateTimeString(),
                ];
            });
            
    return response()->json($comments, 200);
         }
}
