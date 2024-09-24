<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotePost extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'promote_posts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'amount',
        'duration',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
