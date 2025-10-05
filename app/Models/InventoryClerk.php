<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryClerk extends Model
{
    protected $table = 'Inventory_Clerks';
    protected $primaryKey = 'Emp_ID';
    public $incrementing = false;

    protected $fillable = [
        'Emp_ID', 'Forklift_Certification', 'Items_Processed_PerDay', 'inventory_accuracy'
    ];

   public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_ID', 'Emp_ID');
    }
}

