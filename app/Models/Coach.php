<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LearningCategory;
use App\Models\CoachingVideo;

class Coach extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $appends = [
        'image',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'biography',
        'learning_category_id',
        'created_at',
        'updated_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('videos');
    }

    public function getImageAttribute()
    {
        return $this->getMedia('image')->last();
    }
    public function getVideoAttribute()
    {
        return $this->getMedia('video')->last();
    }
    public function coachingVidoes()
    {
        return $this->hasMany(CoachingVideo::class);
    }
    public function learning_category()
    {
        return $this->belongsTo(LearningCategory::class, 'learning_category_id');
    }
}
