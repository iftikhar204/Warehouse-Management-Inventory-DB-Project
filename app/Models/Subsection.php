<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsection extends Model
{
    protected $table = 'subsections';
    protected $primaryKey = 'Sub_Section_ID';
    protected $fillable = [
        'sub_Hype',
        'Sub_Capacity',
        'subsection_name'
    ];

    public function sections()
    {
        return $this->belongsToMany(WarehouseSection::class, 'divides', 'sub_section_ID', 'section_ID');
    }

    public function coldStorage()
    {
        return $this->hasOne(ColdStorage::class, 'sub_section_ID');
    }

    public function bulkStorage()
    {
        return $this->hasOne(BulkStorage::class, 'sub_section_ID');
    }

    public function hazardousMaterials()
    {
        return $this->hasOne(HazardousMaterials::class, 'sub_section_ID');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'sub_section_ID');
    }
    public function warehouseSections()
    {
        return $this->belongsToMany(
            WarehouseSection::class,
            'Divides',
            'Sub_Section_ID',
            'section_ID'
        )->withTimestamps();
    }
    public function warehouses()
    {

        return $this->hasManyThrough(
            Warehouse::class,
            WarehouseSection::class,
            'section_ID',
            'warehouse_ID',
            'Sub_Section_ID',
            'section_ID'
        );
    }
}
