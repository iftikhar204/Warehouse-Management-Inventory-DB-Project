<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

// Since 'incoming_orders' has a composite primary key and stores item details,
// it acts like a pivot table but also has additional data.
// You can extend Model or Pivot. Using Model for more flexibility with timestamps etc.
class IncomingOrder extends Model
{
    protected $table = 'incoming_orders'; // Explicitly set table name

    // No increments primary key, use composite primary key
    protected $primaryKey = ['Supplier_ID', 'Order_ID', 'Product_ID'];
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'array'; // Specify key type as array for composite keys

    protected $fillable = [
        'Supplier_ID', 'Order_ID', 'Product_ID', 'expected_delivery',
        'quantity_ordered', 'quantity_received', 'unit_price', 'total_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'Order_ID', 'Order_ID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    // Override the setKeysForSaveQuery method for composite primary keys
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    // Override getKeyForSaveQuery for composite primary keys
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            return $this->getAttribute($this->getKeyName());
        }

        return $this->getAttribute($keyName);
    }
}
