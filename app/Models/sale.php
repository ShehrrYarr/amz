<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_type',
        'vendor_id',
        'customer_name',
        'sold_by',
        'total_amount',
        'discount',
        'paid_amount',
        'due_amount',
        'sale_date',
        'notes',
    ];

     // A Sale has many SaleMobiles (mobiles in this sale)
    public function saleMobiles()
    {
        return $this->hasMany(saleMobile::class);
    }

    // A Sale may belong to a Vendor
    public function vendor()
    {
        return $this->belongsTo(vendor::class);
    }

    // A Sale was created by a User
    public function seller()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }
    public function mobiles() {
    return $this->hasMany(saleMobile::class);
}

// total after discount
    public function getGrandTotalAttribute()
    {
        $total = (float)($this->total_amount ?? 0);
        $discount = (float)($this->discount ?? 0);
        return max($total - $discount, 0);
    }

    // remaining due (grand_total - paid_amount)
    public function getBalanceAttribute()
    {
        $paid = (float)($this->paid_amount ?? 0);
        return max($this->grand_total - $paid, 0);
    }

}
