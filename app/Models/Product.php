<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'Product_ID';
    protected $fillable = [
        'Product_Name',
        'barcode',
        'Type_ID',
        'Product_Date',
        'weight',
        'dimensions',
        'image',
        'description',
        'price',
        'status'
    ];
    protected $casts = [
        'Product_Date' => 'date',
    ];
    public function types()
    {
        return $this->belongsToMany(ProductType::class, 'belongs_tos', 'Product_ID', 'Type_ID');
    }
    public function type()
    {
        return $this->belongsToMany(ProductType::class, 'belongs_tos', 'Product_ID', 'Type_ID');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'Product_ID', 'Product_ID');
    }


    public function incomingOrders()
    {
        return $this->hasMany(IncomingOrder::class, 'Product_ID');
    }

    public function outgoingOrders()
    {
        return $this->hasMany(OutgoingOrder::class, 'Product_ID');
    }
    public function warehouseSection()
    {
        return $this->belongsTo(WarehouseSection::class, 'section_ID');
    }
}
