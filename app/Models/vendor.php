<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'office_address', 'city', 'CNIC', 'mobile_no','picture'
    ];

    public function mobiles()
    {
        return $this->hasMany(Mobile::class);
    }
}
