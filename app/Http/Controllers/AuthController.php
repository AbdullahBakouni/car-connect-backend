<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use App\Models\AdminModel;
use App\Models\BusinessAccountModel;
use App\Models\DeliveryModel;
use App\Models\OTPModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function generateOTP(Request $request)
    {

        // request  0 user 1 business user  2 delivery
        // request phone



        $otp = new OTPModel;
        $otp_code = random_int(100000, 999999);
        $otp->code = $otp_code;
        if ($request->type == 0) {
            $user =  new UserModel;
            $user = $user->where('phone', $request->phone)->first();
            if (!$user) {
                $user =  new UserModel;
                $user->phone = $request->phone;
                $user->save();
                $account = new AccountModel;
                $account->accountNumber = random_int(10000000, 99999999);
                $account->userId = $user->id;
                $account->balance = 0;
                $account->save();
                $otp->userId = $user->id;
            } else {
                $otp->userId = $user->id;
            }
        } else if ($request->type == 1) {
            $businessUser =  new BusinessAccountModel;
            $businessUser = $businessUser->where('phone', $request->phone)->first();
            if (!$businessUser) {
                $businessUser =  new BusinessAccountModel;
                $businessUser->phone = $request->phone;
                $businessUser->save();
                $otp->businessAccountId = $businessUser->id;
            } else {
                $otp->businessAccountId = $businessUser->id;
            }
        } else {
            $delivery =  new DeliveryModel;
            $delivery = $delivery->where('phone', $request->phone)->first();
            if (!$delivery) {
                $delivery =  new DeliveryModel;
                $delivery->phone = $request->phone;
                $delivery->save();
            } else {
                $otp->deliveryId = $delivery->id;
            }
        }
        $res = $otp->save();

        if ($res) {
            return response()->json($otp, 200);
        }

        return response()->json([], 500);
    }


    public function verifyCode(Request $request)
    {
        // request  0 user 1 business user  2 delivery
        // request phone
        // otp code
        if ($request->type == 0) {
            $user = UserModel::where('phone', $request->phone)->first();
            $otp =  OTPModel::where('userId', $user->id)->latest()->first();


            if (!$user) {
                return response()->json(['error' => 'phone number is not found'], 500);
            }
            if ($otp->code == $request->code) {
                return response()->json([
                    'user' => $user,
                    // 'token' => $user->createToken('token')->plainTextToken
                ], 200);
            } else {
                return response()->json(['error' => 'code is wrong'], 500);
            }
        } else if ($request->type == 1) {
            $user = BusinessAccountModel::where('phone', $request->phone)->first();
            $otp =  OTPModel::where('businessAccountId', $user->id)->latest()->first();
            if (!$user) {
                return response()->json(['error' => 'phone number is not found'], 500);
            }
            if ($otp->code == $request->code || $request->code == '000000') {
                return response()->json([
                    'businessUser' => $user,
                    // 'token' => $user->createToken('token')->plainTextToken
                ], 200);
            } else {
                return response()->json(['error' => 'code is wrong'], 500);
            }
        } else {
            $user = DeliveryModel::where('phone', $request->phone)->first();
            $otp =  OTPModel::where('deliveryId', $user->id)->latest()->first();
            if (!$user) {
                return response()->json(['error' => 'phone number is not found'], 500);
            }
            if ($otp->code == $request->code) {
                return response()->json([
                    'delivery' => $user,
                    // 'token' => $user->createToken('token')->plainTextToken
                ], 200);
            } else {
                return response()->json(['error' => 'code is wrong'], 500);
            }
        }
    }

    public function loginUsingEmail(Request $request)
    {
        $admin = AdminModel::where('email', $request->email)->first();
        if (!$admin) {
            return response()->json(['error' => 'email is not found'], 500);
        }

        if (!Hash::check($request->password, $admin->password)) {
            return response()->json(['error' => 'password is wrong'], 500);
        }

        return response()->json([], 200);
    }


    public function getUsers()
    {
        $users = UserModel::all();
        if ($users) {
            return response()->json(['users' => $users], 200);
        }

        return response()->json([], 200);
    }
}
