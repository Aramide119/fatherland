<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dynasty extends Model
{
    use HasFactory;

    public $guarded=[];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }
    
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->link = Uuid::uuid4()->toString();
        });

        static::creating(function ($dynasty) {
            $dynasty->status = 'pending';
        });
    }
}
