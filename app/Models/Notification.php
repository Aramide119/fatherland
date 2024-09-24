<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded= [];
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
    public function dynasty()
    {
        return $this->belongsTo(Dynasty::class, 'dynasty_id');
    }
}
