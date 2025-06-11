<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\BusinessAccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BusinessUserController extends Controller
{
    public function addBusinessUserProfileInfo(Request $request)
    {
        $user = BusinessAccountModel::find($request->id);
        if (!$user) {
            return response()->json(['error' => 'user is not found'], 500);
        }
        if (!$request->file('commercialRegisterImageUrl') && $request->type == 1) {
            return response()->json(['error' => 'commercial register  is required'], 500);
        }

        if ($request->file('idImageUrl')) {
            Storage::delete('public/' . $user->idImageUrl);
            $image = $request->file('idImageUrl')->store('public');
            $user->idImageUrl = basename($image);
        } else {
            return response()->json(['error' => 'Id image is required'], 500);
        }

        if ($request->file('commercialRegisterImageUrl')) {
            Storage::delete('public/' . $user->commercialRegisterImageUrl);
            $image = $request->file('commercialRegisterImageUrl')->store('public');
            $user->commercialRegisterImageUrl = basename($image);
        }

        $user->name = $request->name;
        $user->desc = $request->desc;
        $user->lat = $request->lat;
        $user->long = $request->long;
        $user->type = $request->type;

        $user = $user->save();
        if ($user) {
            return response()->json([], 200);
        }

        return response()->json(['can not add profile info'], 500);
    }

    public function getBusinessUsers()
    {
        $users = BusinessAccountModel::where('type', 0)->get();
        if ($users) {
            return response()->json($users, 200);
        }
        return response()->json([], 500);
    }

    public function getBusinessUser(Request $request)
    {
        try {
            $user = BusinessAccountModel::find($request->id);
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $response = [
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "phone" => $user->phone,
                    "desc" => $user->desc,
                    "type" => $user->type,
                    "lat" => $user->lat,
                    "long" => $user->long,
                    "idImageUrl" => $user->idImageUrl,
                    "commercialRegisterImageUrl" => $user->commercialRegisterImageUrl,
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getCompanyUsers()
    {
        $users = BusinessAccountModel::where('type', 1)->get();
        if ($users) {
            return response()->json($users, 200);
        }
        return response()->json([], 500);
    }
}
