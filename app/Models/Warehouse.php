<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
     protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_ID';
    protected $fillable = [
        'address_ID', 'Name', 'Max_Capacity', 'operational_status'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_ID');
    }

    public function contacts()
{
    return $this->belongsToMany(
        ContactNumber::class,
        'Warehouse_Contacts',
        'warehouse_ID',
        'contact_ID'
    );
}

    public function subsections()
    {

        return $this->hasManyThrough(
            Subsection::class,
            WarehouseSection::class,
            'warehouse_ID',
            'section_ID',
            'warehouse_ID',
            'section_ID'
        );
    }
    public function warehouseSections()
    {

        return $this->belongsToMany(
            WarehouseSection::class,
            'Includes',
            'warehouse_ID',
            'section_ID'
        )->withTimestamps();
    }
    public function sections()
    {

        return $this->belongsToMany(
            WarehouseSection::class,
            'Includes',
            'warehouse_ID',
            'section_ID'
        )->withTimestamps();
    }

}
