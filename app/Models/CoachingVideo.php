<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LearningCategory;
use App\Models\Coach;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CoachingVideo extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    public function learning_category()
    {
        return $this->belongsTo(LearningCategory::class, 'learning_category_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
    
    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('videos');
    }

     public function getVideoAttribute()
    {
        return $this->getMedia('video')->last();
    }
}
