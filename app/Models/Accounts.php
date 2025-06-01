<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    use HasFactory;
    protected $fillable = ['vendor_id', 'category', 'amount','description','created_by'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function creator() {
    return $this->belongsTo(User::class, 'created_by');
}
}
