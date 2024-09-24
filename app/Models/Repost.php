<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repost extends Model
{
    use HasFactory;

     // Relationship to get the user who made the repost
     public function user()
     {
         return $this->belongsTo(User::class);
     }
 
     // Relationship to get the original post being reposted
     public function post()
     {
         return $this->belongsTo(Post::class, 'post_id');
     }
}
