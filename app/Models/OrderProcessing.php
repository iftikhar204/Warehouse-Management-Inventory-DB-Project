<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProcessing extends Model
{
    protected $table = 'order_processings';
    protected $fillable = [
        'Emp_ID', 'Order_ID', 'Processing_Start', 'Processing_End', 'Status', 'notes'
    ];

    public function orderProcessor()
    {
        return $this->belongsTo(OrderProcessor::class, 'Emp_ID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'Order_ID');
    }
}
