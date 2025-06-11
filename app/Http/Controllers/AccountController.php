<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function addBalance(Request $request)
    {
        try {
            $account = null;
            
            if ($request->userId) {
                $account = AccountModel::where('userId', $request->userId)->first();
            } elseif ($request->businessUserId) {
                $account = AccountModel::where('businessUserId', $request->businessUserId)->first();
            }

            if (!$account) {
                return response()->json(['error' => 'Account not found'], 404);
            }

            $account->balance += $request->amount;
            $res = $account->save();

            if ($res) {
                return response()->json($account, 200);
            }

            return response()->json(['error' => 'Cannot add balance'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function getAccount(Request $request)
    {
        try {
            $account = null;
            
            if ($request->userId) {
                $account = AccountModel::where('userId', $request->userId)->first();
            } elseif ($request->businessUserId) {
                $account = AccountModel::where('businessUserId', $request->businessUserId)->first();
            }

            if (!$account) {
                return response()->json(['error' => 'Account not found'], 404);
            }

            return response()->json($account, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function deductBalance(Request $request)
    {
        try {
            $account = null;
            
            if ($request->userId) {
                $account = AccountModel::where('userId', $request->userId)->first();
            } elseif ($request->businessUserId) {
                $account = AccountModel::where('businessUserId', $request->businessUserId)->first();
            }

            if (!$account) {
                return response()->json(['error' => 'Account not found'], 404);
            }

            if ($account->balance < $request->amount) {
                return response()->json(['error' => 'Insufficient balance'], 400);
            }

            $account->balance -= $request->amount;
            $res = $account->save();

            if ($res) {
                return response()->json($account, 200);
            }

            return response()->json(['error' => 'Cannot deduct balance'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}

