<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function mobiles()
{
    return $this->hasMany(Mobile::class);
}
}
