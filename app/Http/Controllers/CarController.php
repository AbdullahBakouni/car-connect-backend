<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccountModel;
use App\Models\CarModel;
use App\Models\ImageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function addCar(Request $request)
    {
        $car = new CarModel;
        $user = BusinessAccountModel::where('id', $request->id)->first();
        if (!$user) {
            return response()->json(['error' => 'user is not found'], 500);
        }

        if ($user->type == 0 && !$request->file('ownerShipImageUrl')) {
            return response()->json(['error' => 'owner ship image is required'], 500);
        } else if ($user->type == 0 && $request->file('ownerShipImageUrl')) {
            $image = $request->file('ownerShipImageUrl')->store('public');
            $car->ownerShipImageUrl = basename($image);
        }
        $car->desc = $request->desc;
        $car->killo = $request->killo;
        $car->colorId = $request->colorId;
        $car->gearId = $request->gearId;
        $car->brandId = $request->brandId;
        $car->modelId = $request->modelId;
        $car->userId = $request->userId;
        $res = $car->save();

        if (!$request->file('image0')) {
            return response()->json(['error' => 'images are required'], 500);
        } else {
            for ($i = 0; $i < count($request->files); $i++) {
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

        return response()->json(['can not add car'], 500);
    }

    public function getCarsByBusinessUserId(Request $request) {
            $cars =  CarModel::where('userId', $request->userId)->get();
            $message = [];
            for($i=0;$i<count($cars); $i++){
               $images = ImageModel::where('carId',$cars[$i]->id)->get();
               array_push($message,[
                'car' => $cars[$i],
                'images' => $images
               ]);

            }
            if($cars){
            return response()->json(['cars' => $message], 200);

            }
        return response()->json(['can not add car'], 500);

    }

    public function getNewestCars(){
        $cars = CarModel::all()->sortBy("created_at");
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
        return response()->json(['can not add car'], 500);


    }
    public function getCars()
    {
        $cars = CarModel::all();
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
        return response()->json(['can not add car'], 500);
    }
}
