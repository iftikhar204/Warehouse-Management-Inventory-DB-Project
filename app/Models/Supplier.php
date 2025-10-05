<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'Supplier_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'address_ID',
        'Supplier_Name',
        'reliability_rating',
        'lead_time_days'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_ID');
    }

    public function contacts()
    {
        return $this->belongsToMany(ContactNumber::class, 'supplier_contacts', 'Supplier_ID', 'contact_ID');
    }

    public function incomingOrders()
    {
        return $this->hasMany(IncomingOrder::class, 'Supplier_ID');
    }

    public function orderTracking()
    {
        return $this->hasMany(OrderTracking::class, 'Supplier_ID');
    }
}
