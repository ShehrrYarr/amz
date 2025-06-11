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
        'company_id',
        'group_id',
        'battery_health',
        'added_by'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function original_owner()
    {
        return $this->belongsTo(User::class);
    }
    // public function transfers()
    // {
    //     return $this->hasMany(mobile_transfer::class);
    // }

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


    public function transactions()
{
    return $this->hasMany(MobileTransaction::class);
}

public function latestTransaction()
{
    return $this->hasOne(MobileTransaction::class)->latestOfMany();
}

public function latestPurchase()
{
    return $this->hasOne(MobileTransaction::class)
                ->where('category', 'Purchase')
                ->latestOfMany();
}

public function latestSale()
{
    return $this->hasOne(MobileTransaction::class)
                ->where('category', 'Sale')
                ->latestOfMany();
}
public function latestVendorTransaction()
{
    return $this->hasOne(MobileTransaction::class)
        ->where('category', 'Purchase')
        ->latestOfMany('created_at');
}

public function latestSaleTransaction()
{
    return $this->hasOne(MobileTransaction::class)
        ->where('category', 'Sale')
        ->latestOfMany('created_at'); // or 'created_at' if you’re not using 'transaction_date'
}



}
