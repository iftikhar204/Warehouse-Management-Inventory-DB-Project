<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'Order_ID';
    protected $fillable = [
        'shipping_address_ID', 'Shipping_Date', 'Status', 'priority',
        'notes', 'total_amount', 'processed_at', 'shipped_at', 'delivered_at'
    ];
    protected $casts = [
        'Shipping_Date' => 'datetime', // <--- Add this line
    ];

    // No 'order_type' directly on Order model, it's inferred/managed by context

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_ID', 'address_ID');
    }

    // This is no longer a 'hasOne' to a single incoming_order,
    // but a 'hasMany' to the incoming_orders pivot-like table for items
    public function incomingOrderItems()
    {
        return $this->hasMany(IncomingOrder::class, 'Order_ID', 'Order_ID');
    }

    // This is no longer a 'hasMany' to OutgoingOrderItem, but to the outgoing_orders pivot table for items
    public function outgoingOrderItems()
    {
        return $this->hasMany(OutgoingOrder::class, 'Order_ID', 'Order_ID');
    }

    public function tracking()
    {
        return $this->hasMany(OrderTracking::class, 'Order_ID', 'Order_ID');
    }

    // Accessor to determine order type based on related items
    public function getOrderTypeAttribute()
    {
        if ($this->incomingOrderItems()->exists()) {
            return 'incoming';
        } elseif ($this->outgoingOrderItems()->exists()) {
            return 'outgoing';
        }
        return null; // Or 'unknown', or throw an error, depending on desired behavior
    }

    // Accessor to get the main supplier for an incoming order (assuming one supplier per incoming order)
    public function getSupplierAttribute()
    {
        if ($this->order_type === 'incoming' && $this->incomingOrderItems->isNotEmpty()) {
            // Get the supplier from the first incoming item (assuming all items in an incoming order are from the same supplier)
            return $this->incomingOrderItems->first()->supplier;
        }
        return null;
    }

    // Accessor to get the main distributor for an outgoing order (assuming one distributor per outgoing order)
    public function getDistributorAttribute()
    {
        if ($this->order_type === 'outgoing' && $this->outgoingOrderItems->isNotEmpty()) {
            // Get the distributor from the first outgoing item (assuming all items in an outgoing order go to the same distributor)
            return $this->outgoingOrderItems->first()->distributor;
        }
        return null;
    }
     public function getRelatedPartyNameAttribute()
    {
        if ($this->order_type === 'incoming') {
            // Check if incomingOrderItems is loaded and not empty
            if ($this->relationLoaded('incomingOrderItems') && $this->incomingOrderItems->isNotEmpty()) {
                // Get the supplier from the first incoming item
                return $this->incomingOrderItems->first()->supplier->Supplier_Name ?? 'N/A';
            }
            return 'N/A (Incoming)';
        } elseif ($this->order_type === 'outgoing') {
            // Check if outgoingOrderItems is loaded and not empty
            if ($this->relationLoaded('outgoingOrderItems') && $this->outgoingOrderItems->isNotEmpty()) {
                // Get the distributor from the first outgoing item
                return $this->outgoingOrderItems->first()->distributor->Distributor_Name ?? 'N/A';
            }
            return 'N/A (Outgoing)';
        }

        return 'N/A'; // Default for unknown type
    }
}
