<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function sales()
    {
        return $this->hasOne(User::class, 'id', 'sales_id');
    }
}
