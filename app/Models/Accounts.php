<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    use HasFactory;
    protected $fillable = ['vendor_id', 'category', 'amount','description'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
