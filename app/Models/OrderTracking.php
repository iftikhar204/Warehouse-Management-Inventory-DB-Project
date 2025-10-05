<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    protected $primaryKey = 'Tracking_ID';
    protected $table = 'order_trackings'; // Ensure correct table name
    protected $fillable = [
        'Order_ID', 'Supplier_ID', 'Distributor_ID', 'status_change',
        'Timestamp', 'Tracking_Comments'
        // 'Emp_ID' if you want to link to an employee who performed the action
    ];
     protected $casts = [
        'Timestamp' => 'datetime', // <--- Add this line to cast Timestamp to a Carbon object
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'Order_ID', 'Order_ID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'Distributor_ID', 'Distributor_ID');
    }

    // If 'Emp_ID' is added to order_trackings table
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'Emp_ID', 'Emp_ID');
    // }
}
