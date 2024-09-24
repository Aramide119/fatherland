<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Interest extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'interests';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function getCategorySelect()
    {
        $interest = self::pluck('name', 'id')->toArray();

        return $interest;
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
