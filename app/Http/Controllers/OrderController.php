<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use App\Models\CarModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function addOrder(Request $request)
    {
        Log::info('Add Order Request Received', [
            'paymentType' => $request->paymentType,
            'carId' => $request->carId,
            'userId' => $request->userId,
        ]);

        $order = new OrderModel;
        $order->status = "0";
        $order->paymentType = $request->paymentType;
        $order->carId = $request->carId;
        $order->lat = $request->lat;
        $order->long = $request->long;
        $order->date = $request->date;
        $order->userId = $request->userId;
        $order->totalPrice = "";

        $car = CarModel::where('id', $request->carId)->first();

        if (!$car) {
            Log::error('Car not found');
            return response()->json(['error' => 'Car not found'], 404);
        }

        if ($request->paymentType == 1) {
            Log::info('E-Payment selected');
            $account = AccountModel::where('userId', $request->userId)->first();

            if (!$account) {
                Log::error('Account not found for user', ['userId' => $request->userId]);
                return response()->json(['error' => 'Account not found'], 500);
            }

            if ($car->price > $account->balance) {
                Log::warning('Insufficient balance', [
                    'balance' => $account->balance,
                    'price' => $car->price
                ]);
                return response()->json(['error' => 'no enough balance'], 500);
            } else {
                $account->balance -= $car->price;
                $account->save();
                Log::info('Payment processed, new balance', ['balance' => $account->balance]);
            }
        } else {
            Log::info('Cash payment selected');
        }

        if ($car->available == 1) {
            $car->available = 0;
            $car->save();
            Log::info('Car marked as unavailable');
        } else {
            Log::warning('Car already unavailable');
            return response()->json(['error' => 'car is unavailable'], 500);
        }

        $res = $order->save();

        if ($res) {
            Log::info('Order saved successfully', ['orderId' => $order->id]);
            return response()->json([], 200);
        }

        Log::error('Order saving failed');
        return response()->json(['error' => 'can not complete order'], 500);
    }



    public function changeOrderStatus(Request $request)
    {
        $order = OrderModel::where('id', $request->orderId)->first();
        if ($order) {
            if ($order->status == 1) {
                return response()->json(['error' => 'order is accepted'], 500);
            }
            $order->status = "1";
            $res = $order->save();
            if ($res) {
                return response()->json([], 200);
            } else {
                return response()->json(['error' => 'can not complete process'], 500);
            }
        } else {
            return response()->json(['error' => 'order is not found'], 500);
        }
    }

    public function getOrderByUserId(Request $request)
    {
        $orders = OrderModel::where('userId', $request->userId)->get();
        $message = [];
        for ($i = 0; $i < count($orders); $i++) {
            array_push($message, [

                'order' => $orders[$i],

                'car' => CarModel::find($orders[$i]->carId)->first()
            ]);
        }
        if ($orders) {
            return response()->json([
                "orders" => $message
            ], 200);
        }
        return response()->json([], 500);
    }

    public function getOrderByCompanyId(Request $request)
    {
        $businessUserId = $request->id;

        $orders = OrderModel::whereHas('car', function ($query) use ($businessUserId) {
            $query->where('userId', $businessUserId);
        })->get();

        $message = [];

        foreach ($orders as $order) {
            array_push($message, [
                'order' => $order,
                'car' => CarModel::find($order->carId)
            ]);
        }

        if ($orders) {
            return response()->json([
                "orders" => $message
            ], 200);
        }

        return response()->json([], 500);
    }

    public function getOrderToDelivery()
    {
        $orders = OrderModel::where('status', "1")->get();
        $message = [];
        for ($i = 0; $i < count($orders); $i++) {
            array_push($message, [
                'order' => $orders[$i],
                'car' => CarModel::find($orders[$i]->carId)->first()
            ]);
        }
        if ($message) {
            return response()->json($message, 200);
        }
        return response()->json([], 500);
    }
}
