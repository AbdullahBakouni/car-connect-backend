<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function addBalance(Request $request){
        $account = AccountModel::where('userId',$request->userId)->first();
        $account->balance = $request->balance;
        $res = $account->save();
        if($res){
            return response()->json($account, 200);

        }
        return response()->json(['error' => 'can not add balance'], 500);

    }

    public function getUserPayCard(Request $request)
    {
        $account = AccountModel::where('userId', $request->userId)->first();
         if ($account) {
            return response()->json($account, 200);
        }
        return response()->json(['error' => 'can find account'], 500);
    }
}

