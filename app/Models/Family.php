<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Family extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const ACCOUNT_TYPE_SELECT = [
        'public'   => 'Public',
        'private' => 'Private',
    ];

    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy()
    {
        return $this->morphTo();
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'family_members', 'family_id', 'member_id');
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_families')->withPivot('member_type');
    }

    public function familyRequests()
    {
        return $this->belongsToMany(User::class, 'family_requests');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function blockingUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_families')->withTimestamps();
    }

    public function reportfamily()
    {
        return $this->hasMany(ReportFamily::class, 'reported_families', 'user_id', 'family_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->link = Uuid::uuid4()->toString();
        });

        static::creating(function ($family) {
            $family->status = 'pending';
            $family->account_type = 'public';
        });
    }

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('reference');

    }

}
