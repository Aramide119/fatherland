<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CoachingVideo;

class LearningCategory extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'learning_categories';

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
        $categories = self::pluck('name', 'id')->toArray();

        return $categories;
    }
    public function coachingVideos()
    {
        return $this->hasMany(CoachingVideo::class);
    }
}
