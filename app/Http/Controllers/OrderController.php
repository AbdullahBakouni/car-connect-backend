<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use App\Models\CarModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\BusinessAccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function addOrder(Request $request)
    {
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
            return response()->json(['error' => 'Car not found'], 404);
        }

        if ($request->paymentType == 1) {
            $account = AccountModel::where('userId', $request->userId)->first();

            if (!$account) {
                return response()->json(['error' => 'Account not found'], 500);
            }

            if ($car->price > $account->balance) {
                return response()->json(['error' => 'no enough balance'], 500);
            } else {
                $account->balance -= $car->price;
                $account->save();
            }
        }

        if ($car->available == 1) {
            $car->available = 0;
            $car->save();
        } else {
            return response()->json(['error' => 'car is unavailable'], 500);
        }

        $res = $order->save();

        if ($res) {
            return response()->json([], 200);
        }

        return response()->json(['error' => 'can not complete order'], 500);
    }

    public function changeOrderStatus(Request $request)
    {
        try {
            $order = OrderModel::find($request->orderId);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            if (!in_array($request->status, ['0', '1', '2', '3', '4'])) {
                return response()->json(['error' => 'Invalid status code'], 400);
            }

            $order->status = $request->status;
            $res = $order->save();

            if ($res) {
                return response()->json(['message' => 'Order status updated successfully'], 200);
            }

            return response()->json(['error' => 'Failed to update order status'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getOrder(Request $request)
    {
        try {
            $order = OrderModel::find($request->orderId);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getAllOrders(Request $request)
    {
        try {
            $orders = OrderModel::all();
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
