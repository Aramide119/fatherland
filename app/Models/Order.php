<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_information_id',
        'status',
        'order_number',
        'seller_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billingInformation()
    {
        return $this->belongsTo(BillingInformation::class, 'billing_information_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
