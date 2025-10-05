<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'address_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'street_address',
        'city',
        'state_province',
        'postal_code',
        'country'
    ];

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'address_ID', 'address_ID');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'address_ID', 'address_ID');
    }

    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'address_ID', 'address_ID');
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'address_ID', 'address_ID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_address_ID', 'address_ID');
    }
}
