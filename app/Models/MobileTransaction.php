<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileTransaction extends Model
{
    use HasFactory;
      protected $fillable = [
        'mobile_id',
        'category',
        'cost_price',
        'selling_price',
        'vendor_id',
        'customer_name',
        'transaction_date',
        'user_id',
        'note',
    ];

    public function mobile()
    {
        return $this->belongsTo(Mobile::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
