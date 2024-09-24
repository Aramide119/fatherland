<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public $table = 'payment_methods';

    protected $fillable = [
        'user_id',
        'method',
        'paypal_address',
        'bank_name',
        'account_name',
        'account_number',
        'bank_country'
    ];
}
