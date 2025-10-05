<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $primaryKey = 'Type_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'Type_Name', 'Section_ID', 'storage_requirements'
    ];

    public function section()
    {
        return $this->belongsTo(WarehouseSection::class, 'Section_ID');
    }

    public function products()
    {
       return $this->belongsToMany(Product::class, 'belongs_tos', 'Type_ID', 'Product_ID');
    }
   public function warehouseSection()
{
    return $this->belongsTo(WarehouseSection::class, 'Sub_section_ID', 'Sub_section_ID');
}

}


