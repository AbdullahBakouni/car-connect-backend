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
use Illuminate\Support\Facades\DB;

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
            if ($request->paymentType == 1) {
                $buyerAccount = AccountModel::where('userId', $request->userId)->first();
                $car = CarModel::find($request->carId);
                $sellerAccount = AccountModel::where('businessUserId', $car ? $car->userId : null)->first();

                $transaction = new \App\Models\Transaction();
                $transaction->from_account_id = $buyerAccount ? $buyerAccount->id : null;
                $transaction->to_account_id = $sellerAccount ? $sellerAccount->id : null;
                $transaction->order_id = $order->id;
                $transaction->status = 'pending';
                $transaction->amount = $car ? $car->price : 0;
                $transaction->save();

                $escrow = new \App\Models\Escrow();
                $escrow->buyer_id = $request->userId;
                $escrow->transaction_id = $transaction->transaction_id;
                $escrow->seller_id = $car ? $car->userId : null;
                $escrow->status = 'pending';
                $escrow->save();
            }
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
            $orders = OrderModel::with('car')->get();
            $ordersWithCar = $orders->map(function($order) {
                $orderArr = $order->toArray();
                $orderArr['car'] = $order->car;
                return $orderArr;
            });
            return response()->json($ordersWithCar, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getOrderByUserId(Request $request)
    {
        try {
            $orders = OrderModel::where('userId', $request->userId)
                ->with('car')
                ->get();

            return response()->json(['orders' => $orders], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getOrderByCompanyId(Request $request)
    {
        try {
            Log::info('Starting getOrderByCompanyId with companyId: ' . $request->companyId);

            // Validate request
            if (!$request->has('companyId')) {
                Log::error('companyId is missing in request');
                return response()->json(['error' => 'companyId is required'], 400);
            }

            // Get cars for the company
            $cars = CarModel::where('userId', $request->companyId)->pluck('id');
            Log::info('Found cars for company: ' . json_encode($cars));

            if ($cars->isEmpty()) {
                Log::info('No cars found for company');
                return response()->json(['orders' => []], 200);
            }

            // Get orders with relationships
            $orders = OrderModel::whereIn('carId', $cars)
                 ->with('car', 'user')
                ->get();

            Log::info('Found orders: ' . $orders->count());

            return response()->json([
                'orders' => $orders,
                'total_orders' => $orders->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in getOrderByCompanyId: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

        public function releaseEscrow(Request $request)
        {
            $orderId = $request->order_id;
            $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            $escrow = \App\Models\Escrow::where('transaction_id', $transaction->transaction_id)->first();
            if (!$escrow) {
                return response()->json(['error' => 'Escrow not found'], 404);
            }
            if ($escrow->status !== 'pending' || $transaction->status !== 'pending') {
                return response()->json(['error' => 'Escrow or transaction already completed or cancelled'], 400);
            }
            // تحويل الرصيد للبائع
            $sellerAccount = \App\Models\AccountModel::where('businessUserId', $escrow->seller_id)->first();
            if (!$sellerAccount) {
                return response()->json(['error' => 'Seller account not found'], 404);
            }
            $sellerAccount->balance += $transaction->amount;
            $sellerAccount->save();
             $transaction->status = 'completed';
            $transaction->save();
            $escrow->status = 'released';
            $escrow->save();
            return response()->json(['message' => 'Escrow released and transaction completed'], 200);
        }

    public function refundEscrow(Request $request)
    {
        $orderId = $request->order_id;
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        $escrow = \App\Models\Escrow::where('transaction_id', $transaction->transaction_id)->first();
        if (!$escrow) {
            return response()->json(['error' => 'Escrow not found'], 404);
        }
        if ($escrow->status !== 'pending' || $transaction->status !== 'pending') {
            return response()->json(['error' => 'Escrow or transaction already completed or cancelled'], 400);
        }
        // إعادة الرصيد للمشتري
        $buyerAccount = \App\Models\AccountModel::where('userId', $escrow->buyer_id)->first();
        if (!$buyerAccount) {
            return response()->json(['error' => 'Buyer account not found'], 404);
        }
        $buyerAccount->balance += $transaction->amount;
        $buyerAccount->save();
        // تحديث حالة الترانزيكشن والاسكرو
        $transaction->status = 'cancelled';
        $transaction->save();
        $escrow->status = 'cancelled';
        $escrow->save();
        return response()->json(['message' => 'Escrow refunded to buyer and transaction cancelled'], 200);
    }
}
