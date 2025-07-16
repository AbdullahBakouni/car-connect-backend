<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    protected $table = 'escrows';
    protected $primaryKey = 'escrow_id';
    protected $fillable = [
        'buyer_id',
        'transaction_id',
        'seller_id',
        'status',
    ];
} 