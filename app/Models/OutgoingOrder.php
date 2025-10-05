<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

// Similar to IncomingOrder, this acts like a pivot table with extra data
class OutgoingOrder extends Model
{
    protected $table = 'outgoing_orders'; // Explicitly set table name

    // No increments primary key, use composite primary key
    protected $primaryKey = ['Order_ID', 'Product_ID'];
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'array'; // Specify key type as array for composite keys

    protected $fillable = [
        'Order_ID', 'Product_ID', 'Distribution_ID', 'quantity_shipped',
        'tracking_number', 'carrier', 'unit_price', 'total_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'Order_ID', 'Order_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    public function distributor()
    {
        // Note: Your migration has 'Distribution_ID' in 'outgoing_orders' table,
        // which references 'distributors.Distributor_ID'.
        return $this->belongsTo(Distributor::class, 'Distribution_ID', 'Distributor_ID');
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
