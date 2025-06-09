<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\ReservationsModel;
use App\Models\UserModel;
use App\Models\BusinessAccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationsController extends Controller
{
    public function addReservation(Request $request)
    {
        try {
            $car = CarModel::find($request->carId);

            if (!$car) {
                return response()->json(['error' => 'Car not found.'], 404);
            }

            $existingReservation = ReservationsModel::where('carId', $request->carId)
                ->where(function($query) use ($request) {
                    $query->where('startDate', $request->startDate)
                        ->orWhere('endDate', $request->startDate)
                        ->orWhere(function($q) use ($request) {
                            $q->where('startDate', '<=', $request->startDate)
                              ->where('endDate', '>=', $request->startDate);
                        });
                })
                ->first();

            if ($existingReservation) {
                return response()->json([
                    'error' => 'Car is already reserved for this date.',
                    'existing_reservation' => $existingReservation
                ], 409);
            }

            $reservation = new ReservationsModel();
            $reservation->carId = $request->carId;
            $reservation->userId = $request->userId;
            $reservation->startDate = $request->startDate;
            $reservation->endDate = $request->endDate;
            $reservation->total = $request->total;
            $reservation->save();

            return response()->json([
                'message' => 'Reservation added successfully.',
                'reservation' => $reservation
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getUserReservations($userId)
    {
        $reservations = ReservationsModel::with('car')
            ->where('userId', $userId)
            ->get();

        return response()->json($reservations);
    }

    public function getBusinessUserReservations(Request $request)
    {
        try {
            $businessUserId = $request->id;
            
            // Get all cars belonging to the business user
            $cars = CarModel::where('userId', $businessUserId)->pluck('id');
            
            // Get reservations for these cars
            $reservations = ReservationsModel::with(['car', 'user'])
                ->whereIn('carId', $cars)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'reservations' => $reservations->map(function($reservation) {
                    return [
                        'id' => $reservation->id,
                        'startDate' => $reservation->startDate,
                        'endDate' => $reservation->endDate,
                        'total' => $reservation->total,
                        'car' => $reservation->car,
                        'user' => [
                            'id' => $reservation->user->id,
                            'phone' => $reservation->user->phone
                        ],
                        'created_at' => $reservation->created_at
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch reservations'], 500);
        }
    }

    public function getAllReservations()
    {
        try {
            $reservations = ReservationsModel::orderBy('created_at', 'desc')->get();
            $formattedReservations = [];

            foreach ($reservations as $reservation) {
                $car = CarModel::find($reservation->carId);
                if (!$car) continue;

                $user = UserModel::find($reservation->userId);
                if (!$user) continue;

                $businessOwner = BusinessAccountModel::find($car->userId);
                if (!$businessOwner) continue;

                $formattedReservations[] = [
                    'reservation' => [
                        'id' => $reservation->id,
                        'startDate' => $reservation->startDate,
                        'endDate' => $reservation->endDate,
                        'total' => $reservation->total,
                        'created_at' => $reservation->created_at
                    ],
                    'car' => [
                        'id' => $car->id,
                        'desc' => $car->desc,
                        'price' => $car->price,
                        'rent' => $car->rent,
                        'available' => $car->available,
                        'killo' => $car->killo,
                        'ownerShipImageUrl' => $car->ownerShipImageUrl
                    ],
                    'customer' => [
                        'id' => $user->id,
                        'phone' => $user->phone
                    ],
                    'businessOwner' => [
                        'id' => $businessOwner->id,
                        'name' => $businessOwner->name,
                        'phone' => $businessOwner->phone,
                        'type' => $businessOwner->type,
                        'desc' => $businessOwner->desc
                    ]
                ];
            }

            return response()->json([
                'reservations' => $formattedReservations
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
