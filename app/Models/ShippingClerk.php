<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingClerk extends Model
{
     protected $table = 'Shipping_Clerks';
    protected $primaryKey = 'Emp_ID';
    public $incrementing = false;

    protected $fillable = [
        'Emp_ID', 'Error_Rate', 'Packages_PerDay', 'shipping_accuracy'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_ID');
    }
}

