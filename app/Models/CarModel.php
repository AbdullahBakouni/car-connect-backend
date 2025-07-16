<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $table = 'car';

    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'carId');
    }
    public function rates()
    {
        return $this->hasMany(RateModel::class, 'carId');
    }

    public function comments()
    {
        return $this->hasMany(CommentModel::class, 'carId');
    }

    public function favorites()
    {
        return $this->hasMany(FavoriteModel::class);
    }
    public function reservations()
    {
        return $this->hasMany(ReservationsModel::class, 'carId');
    }

    public function brand()
    {
        return $this->belongsTo(BrandModel::class, 'brandId');
    }

    public function model()
    {
        return $this->belongsTo(ModelModel::class, 'modelId');
    }

    public function color()
    {
        return $this->belongsTo(ColorModel::class, 'colorId');
    }

    public function gear()
    {
        return $this->belongsTo(GearModel::class, 'gearId');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'userId');
    }
}
