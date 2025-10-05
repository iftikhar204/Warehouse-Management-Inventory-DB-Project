<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactNumber extends Model
{

    protected $table = 'contact_numbers';
    protected $primaryKey = 'contact_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'contact_type',
        'contact_value',
        'is_primary',
    ];
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_contacts', 'contact_ID', 'warehouse_ID');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_contacts', 'contact_ID', 'order_ID');
    }

    public function distributors()
    {
        return $this->belongsToMany(Distributor::class, 'distributor_contacts', 'contact_ID', 'distributor_ID');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_contacts', 'contact_ID', 'supplier_ID');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_contacts', 'contact_ID', 'Emp_ID');
    }
}
