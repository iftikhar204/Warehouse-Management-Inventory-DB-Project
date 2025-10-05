<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProcessor extends Model
{
    protected $table = 'Order_Processors';
    protected $primaryKey = 'Emp_ID';
    public $incrementing = false;

    protected $fillable = [
        'Emp_ID', 'Avg_time_Processing', 'Accuracy_Rate', 'orders_processed'
    ];

   public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_ID', 'Emp_ID');
    }

    public function orderProcessing()
    {
        return $this->hasMany(OrderProcessing::class, 'Emp_ID');
    }
}

