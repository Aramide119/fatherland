<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'product_variations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SIZE_SELECT = [
        'S'   => 'S',
        'M'   => 'M',
        'L'   => 'L',
        'XL'  => 'XL',
        'XXL' => 'XXL',
    ];

    protected $fillable = [
        'size',
        'color',
        'quantity',
        'product_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
