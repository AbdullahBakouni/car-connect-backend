<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'order';

    public function car()
    {
        return $this->belongsTo(CarModel::class, 'carId');
    }
}
