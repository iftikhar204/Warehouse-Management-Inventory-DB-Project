<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseSection extends Model
{
    protected $primaryKey = 'section_ID';
    protected $table = 'Warehouse_Sections';
    protected $fillable = [
        's_Capacity', 'section_name', 'temperature_zone'
    ];

   public function subsections()
    {

        return $this->belongsToMany(
            Subsection::class,
            'Divides',
            'section_ID',
            'Sub_Section_ID'
        )->with('stocks.product.types');
    }



    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'section_ID');
    }
    public function includes()
{
    return $this->hasOne(Includes::class, 'section_ID', 'Section_ID');
}
public function warehouses()
    {

        return $this->belongsToMany(
            Warehouse::class,
            'Includes',
            'section_ID',
            'warehouse_ID'
        )->withTimestamps();
    }
}
