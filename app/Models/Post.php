<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ReportPost;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Ramsey\Uuid\Uuid;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('videos');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function generateSlug()
    {
        $baseSlug = 'flpost';
        $uniquePart = Str::uuid()->toString();
        $defaultSlug = $baseSlug . '-' . $uniquePart;
        
        // Check if the content (text) is empty
        if (empty($this->text)) {
            return Str::slug(Str::limit($defaultSlug, 15)); // Limit the slug to the desired maximum length
        }

        return Str::slug(Str::limit($this->text, 15)) . '-' . $uniquePart; // Limit the slug to the desired maximum length
    }    

    public function reportPost()
    {
        return $this->hasMany(ReportPost::class, 'report_posts', 'user_id', 'post_id');
    }


    public function reposts()
    {
        return $this->belongsTo(Post::class, 'repost_id');
    }

    public function promotePostSubscriptions()
    {
        return $this->hasMany(PromotePostSubscription::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
  
}
