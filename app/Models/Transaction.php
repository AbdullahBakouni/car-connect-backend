<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'order_id',
        'status',
        'amount',
    ];
} 