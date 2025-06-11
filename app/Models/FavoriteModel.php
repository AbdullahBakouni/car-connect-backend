<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteModel extends Model
{
    protected $table = 'favorite';

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    public function car()
    {
        return $this->belongsTo(CarModel::class);
    }
}
