<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BrandModel;
use App\Models\BusinessAccountModel;
use App\Models\CarModel;
use App\Models\ColorModel;
use App\Models\GearModel;
use App\Models\ImageModel;
use App\Models\LikeModel;
use App\Models\ModelModel;
use App\Models\RateModel;
use App\Models\ViewModel;
use App\Models\AccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function getCarDetails(Request $request)
    {
        try {
            $car = CarModel::with(['comments.user'])
                ->where('id', $request->id)
                ->first();

            if (!$car) {
                return response()->json(['error' => 'Car not found'], 404);
            }

            $model = ModelModel::find($car->modelId);
            $brand = BrandModel::find($car->brandId);
            $gear = GearModel::find($car->gearId);
            $color = ColorModel::find($car->colorId);
            $images = ImageModel::where('carId', $car->id)->get();
            $avgRate = $car->rates()->avg('value');

            $view = ViewModel::where('carId', $car->id)->first();
            if ($view) {
                $view->count += 1;
                $view->save();
                $viewCount = $view->count;
            } else {
                $view = new ViewModel();
                $view->carId = $car->id;
                $view->count = 1;
                $view->save();
                $viewCount = 1;
            }

            $likesCount = LikeModel::where('carId', $car->id)->count();

            $response = [
                'car' => $car,
                'model' => $model,
                'brand' => $brand,
                'gear' => $gear,
                'color' => $color,
                'images' => $images,
                'avgRate' => round($avgRate, 1),
                'viewCount' => $viewCount,
                'likesCount' => $likesCount
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function addCar(Request $request)
    {
        $car = new CarModel;

        $user = BusinessAccountModel::where('id', $request->id)->first();
        if (!$user) {
            return response()->json(['error' => 'user is not found'], 500);
        }

        if ($user->commercialRegisterImageUrl == null && !$request->file('ownerShipImageUrl')) {
            return response()->json(['error' => 'owner ship image is required'], 500);
        }
        if ($request->file('ownerShipImageUrl')) {
            $image = $request->file('ownerShipImageUrl')->store('public');
            $car->ownerShipImageUrl = basename($image);
            $car->available = 0;
        }
        $car->desc = $request->desc;
        $car->killo = $request->killo;
        $car->colorId = $request->colorId;
        $car->gearId = $request->gearId;
        $car->brandId = $request->brandId;
        $car->modelId = $request->modelId;
        $car->userId = $request->id;
        $car->price = $request->price;
        $car->rent = $request->rent;
        $res = $car->save();

        try {
            $view = new ViewModel;
            $view->carId = $car->id;
            $view->count = 0;
            $view->save();
        } catch (\Exception $e) {
        }

        if (!$request->file('image0')) {
            return response()->json(['error' => 'images are required'], 500);
        } else {
            for ($i = 0; $i < count($request->files) - 1; $i++) {
                $image = $request->file('image' . $i)->store('public');
                $imageModel = new ImageModel;
                $imageModel->imageUrl = basename($image);
                $imageModel->carId = $car->id;
                $imageModel->save();
            }
        }

        if ($res) {
            return response()->json(['car' => $car], 200);
        }

        return response()->json(['error' => 'can not add car'], 500);
    }

    public function getCarsByBusinessUserId(Request $request)
    {
        $cars = CarModel::where('userId', $request->userId)->get();
        $message = [];
        for ($i = 0; $i < count($cars); $i++) {
            $images = ImageModel::where('carId', $cars[$i]->id)->get();
            array_push($message, [
                'car' => $cars[$i],
                'images' => $images
            ]);
        }
        if ($cars) {
            return response()->json(['cars' => $message], 200);
        }
        return response()->json([], 500);
    }

    public function getNewestCars()
    {
        $cars = CarModel::where('available', 1)->orderBy('created_at')->get();
        $message = [];

        foreach ($cars as $car) {
            $images = ImageModel::where('carId', $car->id)->get();
            $message[] = [
                'car' => $car,
                'images' => $images
            ];
        }

        if ($cars) {
            return response()->json(['cars' => $message], 200);
        }
        return response()->json([''], 200);
    }

    public function getCars()
    {
        $cars = CarModel::where('available', 1)->orderBy('created_at', 'desc')->get();
        $message = [];

        foreach ($cars as $car) {
            $images = ImageModel::where('carId', $car->id)->get();
            $message[] = [
                'car' => $car,
                'images' => $images
            ];
        }

        if ($cars) {
            return response()->json(['cars' => $message], 200);
        }

        return response()->json([], 500);
    }

    public function getAllCars()
    {
        $cars = CarModel::all();
        $message = [];

        foreach ($cars as $car) {
            $images = ImageModel::where('carId', $car->id)->get();
            $message[] = [
                'car' => $car,
                'images' => $images
            ];
        }

        if ($cars) {
            return response()->json(['cars' => $message], 200);
        }

        return response()->json([], 500);
    }

    public function getCarsByUserId(Request $request)
    {
        $cars = CarModel::where('userId', $request->id)->get();
        $message = [];
        for ($i = 0; $i < count($cars); $i++) {
            $images = ImageModel::where('carId', $cars[$i]->id)->get();
            array_push($message, [
                'car' => $cars[$i],
                'images' => $images
            ]);
        }
        if ($cars) {
            return response()->json(['cars' => $message], 200);
        }
        return response()->json([''], 500);
    }

    public function getCarsByBrandId(Request $request)
    {
        $cars = CarModel::where('brandId', $request->brandId)
            ->where('available', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $message = [];

        foreach ($cars as $car) {
            $images = ImageModel::where('carId', $car->id)->get();
            $message[] = [
                'car' => $car,
                'images' => $images
            ];
        }

        if ($cars->count()) {
            return response()->json(['cars' => $message], 200);
        }

        return response()->json([], 500);
    }

    public function toggleAvailability(Request $request)
    {
        try {
            $car = CarModel::find($request->carId);
            if (!$car) {
                return response()->json(['error' => 'Car not found'], 404);
            }

            $car->available = !$car->available;
            
            if ($car->save()) {
                return response()->json([
                    'message' => 'Car availability updated successfully',
                    'available' => $car->available
                ], 200);
            }

            return response()->json(['error' => 'Failed to update car availability'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
