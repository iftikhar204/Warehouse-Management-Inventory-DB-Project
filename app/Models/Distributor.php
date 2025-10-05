<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
     protected $table = 'distributors';
    protected $fillable = [
        'Distributor_Name', 'address_ID', 'Distributing_Location', 'tax_id', 'account_manager'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_ID');
    }

    public function contacts()
    {
        return $this->belongsToMany(ContactNumber::class, 'distributor_contacts', 'Distributor_ID', 'contact_ID');
    }

    public function outgoingOrders()
    {
        return $this->hasMany(OutgoingOrder::class, 'Distribution_ID');
    }

    public function orderTracking()
    {
        return $this->hasMany(OrderTracking::class, 'Distributor_ID');
    }
}
