<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function addBalance(Request $request){
        $account =new AccountModel;
        $account->balance = $request->balance;
        $account = $account->save();
        if($account){
            return response()->json($account, 200);

        }
        return response()->json(['error' => 'can not add balance'], 500);

    }
}
