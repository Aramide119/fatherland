<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\ResourceCategory;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Resource extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'resources';

    protected $appends = [
        'image',
        'video',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'title',
        'content',
        'resource_category_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function resource_category()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getImageAttribute()
    {
        return $this->getMedia('image')->last();
    }

    public function getVideoAttribute()
    {
        return $this->getMedia('video')->last();
    }
}
