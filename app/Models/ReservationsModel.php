<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationsModel extends Model
{
    protected $table = 'reservations';
    public function car()
    {
        return $this->belongsTo(CarModel::class, 'carId');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'userId');
    }
}
