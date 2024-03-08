<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'user_id',
        'sales_id',
        'code',
        'booking_date',
        'end_date',
        'name_order',
        'phone',
        'start_time',
        'end_time',
        'address',
        'width',
        'height',
        'total_price',
        'status',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function sales()
    {
        return $this->hasOne(User::class, 'id', 'sales_id');
    }

    public function payment()
    {
        return $this->hasMany(TransactionPayment::class,'transaction_id','id');
    }
}
