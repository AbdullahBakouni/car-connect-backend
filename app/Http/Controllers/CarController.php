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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{

    public function getCarDetails(Request $request)
    {
        Log::info('getCarDetails called', ['carId' => $request->id]);

        try {
            $car = CarModel::with(['comments.user'])
                ->where('id', $request->id)
                ->first();

            if (!$car) {
                Log::warning('Car not found', ['carId' => $request->id]);
                return response()->json(['error' => 'Car not found'], 404);
            }

            Log::info('Car found', ['car' => $car->id]);

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
                Log::info('View record not found for car. Creating new.', ['carId' => $car->id]);
                $view = new ViewModel();
                $view->carId = $car->id;
                $view->count = 1;
                $view->save();
                $viewCount = 1;
            }

            $likesCount = LikeModel::where('carId', $car->id)->count();

            return response()->json([
                'car' => $car,
                'gear' => $gear,
                'model' => $model,
                'color' => $color,
                'brand' => $brand,
                'images' => $images,
                'rate' => $avgRate,
                'views' => $viewCount,
                'likes' => $likesCount,
                'comments' => $car->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'user' => [
                            'id' => $comment->user->id ?? null,
                            'phone' => $comment->user->phone ?? 'Unknown',
                        ],
                        'created_at' => $comment->created_at,
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in getCarDetails', [
                'carId' => $request->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong.'], 500);
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
        // else if ($user->type == 0 && $request->file('ownerShipImageUrl')) {
        //     $image = $request->file('ownerShipImageUrl')->store('public');
        //     $car->ownerShipImageUrl = basename($image);
        // }
        $car->desc = $request->desc;
        $car->killo = $request->killo;
        $car->colorId = $request->colorId;
        $car->gearId = $request->gearId;
        $car->brandId = $request->brandId;
        $car->modelId = $request->modelId;
        $car->userId = $request->id;
        $car->price = $request->price;
        $res = $car->save();

        try {
            $view = new ViewModel;
            $view->carId = $car->id;
            $view->count = 0;
            $view->save();
        } catch (\Exception $e) {
            Log::error('Failed to save view record: ' . $e->getMessage(), [
                'carId' => $car->id,
                'trace' => $e->getTraceAsString()
            ]);
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
        $cars =  CarModel::where('userId', $request->userId)->get();
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
        Log::info('ccccccccccccccccccccccccccccccc ');


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
}
