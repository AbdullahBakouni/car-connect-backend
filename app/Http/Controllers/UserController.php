<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function uploadUserIdImage(Request $request)
    {

        $user = UserModel::find($request->id);
        if (!$user) {
            return response()->json(['error' => 'user is not found'], 500);
        }
        if ($request->file('idImageUrl')) {
            Storage::delete('public/' . $user->idImageUrl);
            $image = $request->file('idImageUrl')->store('public');
            $user->idImageUrl = basename($image);
        } else {
            return response()->json(['error' => 'Id image is required'], 500);
        }

        $user =  $user->save();
        if ($user) {
            return response()->json([], 200);
        }
        return response()->json(['can not add profile info'], 500);
    }

    public function getRecentUsers()
    {
        $users = UserModel::orderBy('created_at', 'desc')->limit(5)->get();
        return response()->json(['recentUsers' => $users], 200);
    }
}
