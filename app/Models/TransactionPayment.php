<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    use HasFactory;

    protected $table = 'transaction_payments';

    protected $fillable = [
        'transaction_id',
        'code',
        'status',
        'payment_url',
        'total_price',
    ];
}
