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
}
