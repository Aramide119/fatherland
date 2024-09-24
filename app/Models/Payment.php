<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $table = 'payments';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'order_id'
    ];
}
