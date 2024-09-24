<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Seller extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'business_name',
        'business_address_country',
        'business_address_state',
        'business_address_city',
        'business_address_postal_code',
        'business_registration_number',
        'business_license',
        'identification_document',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageAttribute()
    {
        return $this->getMedia('images')->last();
    }

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('images');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
