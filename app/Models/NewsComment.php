<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function replies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->with('replies');
    }

}
