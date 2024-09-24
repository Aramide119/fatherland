<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Family;
use App\Models\User;

class ReportFamily extends Model
{
    use HasFactory;
    protected $guarded= [];

    public function family()
    {
      return $this->belongsTo(Family::class);
    }
  
    public function user()
    {
      return $this->belongsTo(User::class);
    }
  
}
