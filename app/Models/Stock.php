<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
     protected $table = 'stocks';
    protected $primaryKey = 'Stock_ID';
     protected $casts = [
        'Use_By_Date' => 'date',
    ];
    protected $fillable = [
        'Quantity', 'Manufactured_Date', 'Use_By_Date', 'batch_number', 'Product_ID', 'Sub_section_ID', 'last_checked'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID');
    }

    public function subsection()
    {
        return $this->belongsTo(Subsection::class, 'Sub_section_ID');
    }
    public function warehouseSection()
{
    return $this->belongsTo(SubSection::class, 'Sub_Section_ID');
}

}

