<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restore extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function restoredBy() {
    return $this->belongsTo(User::class, 'restored_by');
}
}
