<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
     protected $table = 'Stock_Movements';
    protected $primaryKey = 'movement_ID';
    public $incrementing = true;
    protected $fillable = [
        'stock_ID',
        'Product_ID',
        'Sub_section_ID',
        'Emp_ID',
        'change_quantity',
        'old_quantity',
        'new_quantity',
        'reason',
        'notes',
        'movement_type',
        'movement_date',
    ];
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_ID', 'Stock_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    public function subsection()
    {
        return $this->belongsTo(Subsection::class, 'Sub_section_ID', 'Sub_Section_ID');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_ID', 'Emp_ID');
    }
}
