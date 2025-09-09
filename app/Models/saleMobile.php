<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saleMobile extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'mobile_id',
        'selling_price',
         'selling_discounted_price',  // net discounted price
    'discount_share',
    ];

    // SaleMobile belongs to a Sale
    public function sale()
    {
        return $this->belongsTo(sale::class);
    }

    // SaleMobile belongs to a Mobile
    public function mobile()
    {
        return $this->belongsTo(Mobile::class);
    }
}
