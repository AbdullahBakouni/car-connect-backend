<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportModel extends Model
{
    protected $table = 'report';
    
    protected $fillable = [
        'content',
        'carId',
        'userId'
    ];

    public function car()
    {
        return $this->belongsTo(CarModel::class, 'carId');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'userId');
    }
}
