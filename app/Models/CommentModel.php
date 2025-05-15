<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    protected $table = 'comment';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'userId'); 
    }
}
