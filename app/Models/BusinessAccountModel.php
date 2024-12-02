<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BusinessAccountModel extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'business_user';
}
