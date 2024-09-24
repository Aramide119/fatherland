<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Post;
use DateTimeInterface;
use App\Models\ReportPost;
use App\Models\BlockedUser;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\ReportEvent;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, SoftDeletes, Notifiable, HasFactory;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone_number',
        'date_of_birth',
        'profession',
        'education',
        'location',
        'professionLocation',
        'about',
        'university',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function verifyUser()
    {
        return $this->hasMany(VerifyUser::class, 'user_id');
    }

    public function forgotPassword()
    {
        return $this->hasMany(ForgotPassword::class);
    }

    // public function friend()
    // {
    //     return $this->hasMany(Friend::class, 'user_id');
    // }

    protected static function boot()
    {
        parent::boot();

        // Set the default account type to "basic" when creating a new user.
        static::creating(function ($user) {
            $user->plan_type = 'basic';
            $user->account_type = 'public';
        });
    }

    //     public function friend()
    // {
    //     return $this->hasMany(Friend::class, 'user_id');
    // }

    // public function friendRequest()
    // {
    //     return $this->hasMany(Friend::class, 'friend_id');
    // }


    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function acceptedFriends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    public function friendRequestsSent()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }


    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function families()
    {
        return $this->belongsToMany(Family::class, 'user_families')->withPivot('member_type');
    }

    public function familyRequests()
    {
        return $this->belongsToMany(Family::class, 'family_requests');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function dynasties()
    {
        return $this->belongsToMany(Dynasty::class);
    }

    // public function families()
    // {
    //     return $this->belongsToMany(Family::class, 'family_members', 'member_id', 'family_id');
    // }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function dynasty()
    {
        return $this->belongsTo(Dynasty::class);
    }

    public function reportPost()
    {
        return $this->hasMany(ReportPost::class);
    }

    public function reportEvent()
    {
        return $this->hasMany(ReportEvent::class);
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocked_user_id');
    }

    public function blockedFamilies()
    {
        return $this->belongsToMany(Family::class, 'blocked_families', 'user_id', 'family_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function toggleAccountType()
    {
        $this->account_type = $this->account_type === 'private' ? 'public' : 'private';
        $this->save();
    }

    public function likes()
    {
        return $this->belongsToMany(News::class, 'news_likes', 'user_id', 'news_id')
                    ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_attendees')->withTimestamps();
    }

    public function newsComments()
    {
        return $this->hasMany(NewsComment::class);
    }

    public function promotePostSubscriptions()
    {
        return $this->hasMany(PromotePostSubscription::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function subscriptionTransactions()
    {
        return $this->hasMany(SubscriptionTransaction::class, 'user_id');
    }

    public function restaurants()
    {
        return $this->morphMany(Restaurant::class, 'creator');
    }

    public function uniqueNumber()
    {
        return $this->hasOne(UserUniqueNumber::class);
    }
}
