<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
use App\Models\CarModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addOrder(Request $request)
    {
        /*
        paymentType type 0 cash 1 e-payment
        carId 
        userId
        date
        userLat
        userLong
        */
        // status 0 pending 1 accepted 2 ontheway 3 delivered -1 canceled
        $order = new OrderModel;
        $order->status = "0";
        $order->paymentType = $request->paymentType;
        $order->carId = $request->carId;
        $order->lat = $request->lat;
        $order->long = $request->long;
        $order->date = $request->date;
        $order->userId = $request->userId;
        $car  = CarModel::where('id', $request->carId)->first();
        if ($request->paymentType  == 1) {
            $account = AccountModel::where('userId', $request->userId)->first();
            if ($car->price > $account->balance) {
                return response()->json(['error' => 'no enough balance'], 500);
            } else {
                $account->balance = $account->balance - $car->price;
                $account->save();
            }
        }

        if ($car->available == 1) {
            $car->available = 0;
            $car->save();
        } else {
            return response()->json(['error' => 'car is unavailable'], 500);
        }


        $earthRadius = 6371;
        $lat1 = deg2rad($car->lat);
        $lon1 = deg2rad($car->lat);
        $lat2 = deg2rad($request->lat);
        $lon2 = deg2rad($request->lat);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        $order->totalPrice = ($distance) + $car->price;

        $res = $order->save();

        if ($res) {
            return response()->json([], 200);
        }
        return response()->json(['error' => 'can not complete order'], 500);
    }


    public function acceptOrder(Request $request)
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
        if ($message) {
            return response()->json($message, 200);
        }
        return response()->json([], 500);
    }

    public function getOrderByCompanyId(){
        // TODO query needs relations
    }

    public function getOrderToDelivery() {
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
