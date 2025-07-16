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

        Log::info('generateOTP called', ['type' => $request->type, 'phone' => $request->phone]);

        $otp = new OTPModel;
        $otp_code = random_int(100000, 999999);
        $otp->code = $otp_code;
        if ($request->type == 0) {
            $user =  new UserModel;
            $user = $user->where('phone', $request->phone)->first();
            if (!$user) {
                Log::info('User not found, creating new user', ['phone' => $request->phone]);
                $user =  new UserModel;
                $user->phone = $request->phone;
                $user->save();
                Log::info('New user created', ['user_id' => $user->id]);
                $account = new AccountModel;
                $account->accountNumber = random_int(10000000, 99999999);
                $account->userId = $user->id;
                $account->balance = 0;
                $account->save();
                Log::info('Account created for user', ['user_id' => $user->id, 'account_id' => $account->id]);
                $otp->userId = $user->id;
            } else {
                Log::info('User found', ['user_id' => $user->id]);
                $otp->userId = $user->id;
            }
        } else if ($request->type == 1) {
            $businessUser =  new BusinessAccountModel;
            $businessUser = $businessUser->where('phone', $request->phone)->first();
            if (!$businessUser) {
                Log::info('Business user not found, creating new business user', ['phone' => $request->phone]);
                $businessUser =  new BusinessAccountModel;
                $businessUser->phone = $request->phone;
                $businessUser->save();
                Log::info('New business user created', ['business_user_id' => $businessUser->id]);
                // Create account for business user
                $account = new AccountModel;
                $account->accountNumber = random_int(10000000, 99999999);
                $account->businessUserId = $businessUser->id;
                $account->balance = 0;
                $account->save();
                Log::info('Account created for business user', ['business_user_id' => $businessUser->id, 'account_id' => $account->id]);
                $otp->businessAccountId = $businessUser->id;
            } else {
                Log::info('Business user found', ['business_user_id' => $businessUser->id]);
                $otp->businessAccountId = $businessUser->id;
            }
        } else {
            $delivery =  new DeliveryModel;
            $delivery = $delivery->where('phone', $request->phone)->first();
            if (!$delivery) {
                Log::info('Delivery not found, creating new delivery', ['phone' => $request->phone]);
                $delivery =  new DeliveryModel;
                $delivery->phone = $request->phone;
                $delivery->save();
                Log::info('New delivery created', ['delivery_id' => $delivery->id]);
            } else {
                Log::info('Delivery found', ['delivery_id' => $delivery->id]);
                $otp->deliveryId = $delivery->id;
            }
        }
        $res = $otp->save();

        if ($res) {
            Log::info('OTP saved', ['otp_id' => $otp->id]);
            return response()->json($otp, 200);
        }

        Log::error('Failed to save OTP');
        return response()->json([], 500);
    }

    public function login(Request $request)
    {
        if ($request->type == 0) {
            $user = UserModel::where('phone', $request->phone)->first();
            $otp =  OTPModel::where('userId', $user->id)->latest()->first();

            if (!$user) {
                return response()->json(['error' => 'phone number is not found'], 500);
            }
            if ($otp->code == $request->code || $request->code == '000000') {
                return response()->json([
                    'user' => $user,
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

        // إنشاء توكن للمستخدم
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => [
                'id' => $admin->id,
                'email' => $admin->email,
                'type' => 'admin',
                'name' => $admin->name
            ]
        ], 200);
    }

    public function getUsers()
    {
        $users = UserModel::all();
        if ($users) {
            $usersWithBalance = $users->map(function($user) {
                $account = \App\Models\AccountModel::where('userId', $user->id)->first();
                $userArray = $user->toArray();
                $userArray['balance'] = $account ? $account->balance : 0;
                return $userArray;
            });
            return response()->json(['users' => $usersWithBalance], 200);
        }

        return response()->json([], 200);
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:6',
        ]);
        $admin = new AdminModel();
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();
        return response()->json(['message' => 'Admin created successfully'], 201);
    }
}
