<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    use HasFactory;
    protected $fillable = [
        'mobile_name',
        'imei_number',
        'sim_lock',
        'color',
        'storage',
        'cost_price',
        'selling_price',
        'vendor_id',
        'company_id',
        'group_id',
        ' battery_health'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function original_owner()
    {
        return $this->belongsTo(User::class);
    }
    public function transfers()
    {
        return $this->hasMany(mobile_transfer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(vendor::class);
    }
    public function soldVendor()
    {
        return $this->belongsTo(vendor::class);
    }
    public function company()
    {
        return $this->belongsTo(company::class);
    }

    public function group()
    {
        return $this->belongsTo(group::class);
    }

    // Who added the mobile
    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    // Who sold the mobile
    public function soldBy()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    // Who marked the mobile as pending
    public function pendingBy()
    {
        return $this->belongsTo(User::class, 'pending_by');
    }


}
