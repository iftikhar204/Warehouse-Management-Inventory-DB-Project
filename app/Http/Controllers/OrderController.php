<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Address;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Distributor;
use App\Models\IncomingOrder; // Now represents incoming_orders table (items)
use App\Models\OutgoingOrder; // Now represents outgoing_orders table (items)
use App\Models\StockMovement;
use App\Models\OrderTracking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::with([
            'shippingAddress',
            'incomingOrderItems.supplier', // Load supplier for each incoming item
            'incomingOrderItems.product',
            'outgoingOrderItems.distributor', // Load distributor for each outgoing item
            'outgoingOrderItems.product',
        ])
            ->when($status, function ($query, $status) {
                return $query->where('Status', $status);
            })
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::with('stocks')->get();
        $distributors = Distributor::all();
        $suppliers = Supplier::all();

        return view('orders.create', compact('products', 'distributors', 'suppliers'));
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            Log::warning('Order not found for ID: ' . $id);
            abort(404, 'Order not found.');
        }

        $order->load([
            'shippingAddress',
            'tracking', // We'll load supplier/distributor via accessors
        ]);

        // Load specific item types and their relations
        $order->load([
            'incomingOrderItems.supplier',
            'incomingOrderItems.product',
            'outgoingOrderItems.distributor',
            'outgoingOrderItems.product',
        ]);

        // Determine items and related party (supplier/distributor) based on which items exist
        $items = collect();
        $relatedParty = null; // Can be a Supplier or Distributor object
        $orderType = $order->order_type; // Use the accessor

        if ($orderType === 'incoming') {
            $items = $order->incomingOrderItems;
            // Get the supplier from the first item, assuming one supplier per incoming order
            if ($items->isNotEmpty()) {
                $relatedParty = $items->first()->supplier;
            }
        } elseif ($orderType === 'outgoing') {
            $items = $order->outgoingOrderItems;
            // Get the distributor from the first item, assuming one distributor per outgoing order
            if ($items->isNotEmpty()) {
                $relatedParty = $items->first()->distributor;
            }
        }

        return view('orders.show', compact('order', 'items', 'relatedParty'));
    }

    public function process(Request $request, Order $order)
    {
        if ($order->Status !== 'pending') {
            return back()->withErrors(['error' => 'Order must be in "pending" status to be processed.']);
        }

        $order->status = 'processing';
        $order->processed_at = now();
        $order->save();

        // Add tracking entry
        $trackingData = [
            'status_change' => 'processed',
            'Timestamp' => now(),
            'Tracking_Comments' => 'Order moved to processing status.',
            'Order_ID' => $order->Order_ID,
        ];
        if ($order->order_type === 'incoming' && $order->supplier) {
            $trackingData['Supplier_ID'] = $order->supplier->Supplier_ID;
        } elseif ($order->order_type === 'outgoing' && $order->distributor) {
            $trackingData['Distributor_ID'] = $order->distributor->Distributor_ID;
        }
        OrderTracking::create($trackingData); // Create directly using the model

        return redirect()->route('orders.index')->with('success', 'Order status updated to processing!');
    }

    public function ship(Request $request, Order $order)
    {
        if ($order->Status !== 'processing') {
            return back()->withErrors(['error' => 'Order must be in "processing" status to be shipped.']);
        }

        $order->status = 'shipped';
        $order->shipped_at = now();
        $order->save();

        // Add tracking entry
        $trackingData = [
            'status_change' => 'shipped',
            'Timestamp' => now(),
            'Tracking_Comments' => 'Order shipped.',
            'Order_ID' => $order->Order_ID,
        ];
        if ($order->order_type === 'incoming' && $order->supplier) {
            $trackingData['Supplier_ID'] = $order->supplier->Supplier_ID;
        } elseif ($order->order_type === 'outgoing' && $order->distributor) {
            $trackingData['Distributor_ID'] = $order->distributor->Distributor_ID;
        }
        OrderTracking::create($trackingData);

        return redirect()->route('orders.index')->with('success', 'Order status updated to shipped!');
    }


    public function deliver(Request $request, Order $order)
    {
        if ($order->Status !== 'shipped') {
            return back()->withErrors(['error' => 'Order must be in "shipped" status to be delivered.']);
        }

        $order->status = 'delivered';
        $order->delivered_at = now();
        $order->save();

        // Add tracking entry
        $trackingData = [
            'status_change' => 'delivered',
            'Timestamp' => now(),
            'Tracking_Comments' => 'Order delivered successfully.',
            'Order_ID' => $order->Order_ID,
        ];
        if ($order->order_type === 'incoming' && $order->supplier) {
            $trackingData['Supplier_ID'] = $order->supplier->Supplier_ID;
        } elseif ($order->order_type === 'outgoing' && $order->distributor) {
            $trackingData['Distributor_ID'] = $order->distributor->Distributor_ID;
        }
        OrderTracking::create($trackingData);

        return redirect()->route('orders.index')->with('success', 'Order status updated to delivered!');
    }

    public function store(Request $request)
    {
        $rules = [
            'order_type' => 'required|string|in:incoming,outgoing',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'street_address' => 'required|string|max:100',
            'city' => 'required|string|max:50',
            'state_province' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.Product_ID' => 'required|exists:products,Product_ID',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];

        // Conditional rules for Supplier_ID, Distributor_ID, and specific dates
        if ($request->input('order_type') === 'incoming') {
            $rules['Supplier_ID'] = ['required', 'exists:suppliers,Supplier_ID'];
            $rules['expected_delivery'] = ['required', 'date', 'after_or_equal:today'];
        } elseif ($request->input('order_type') === 'outgoing') {
            $rules['Distributor_ID'] = ['required', 'exists:distributors,Distributor_ID'];
            $rules['Shipping_Date'] = ['required', 'date', 'after_or_equal:today'];
            // Added for outgoing specific fields from migration
            $rules['tracking_number'] = 'nullable|string|max:50';
            $rules['carrier'] = 'nullable|string|max:50';
        }

        // Custom validation messages
        $messages = [
            'order_type.required' => 'Please select an order type (Incoming or Outgoing).',
            'order_type.in' => 'Invalid order type selected.',
            'priority.required' => 'Please select the order priority.',
            'priority.in' => 'Invalid priority level.',
            'street_address.required' => 'Street address is required for the shipping address.',
            'street_address.max' => 'Street address cannot exceed 100 characters.',
            'city.required' => 'City is required for the shipping address.',
            'city.max' => 'City name cannot exceed 50 characters.',
            'state_province.max' => 'State/Province name cannot exceed 50 characters.',
            'postal_code.max' => 'Postal code cannot exceed 20 characters.',
            'country.required' => 'Country is required for the shipping address.',
            'country.max' => 'Country name cannot exceed 50 characters.',
            'items.required' => 'At least one item is required for the order.',
            'items.array' => 'Items must be provided as a list.',
            'items.min' => 'Please add at least one product to the order.',
            'items.*.Product_ID.required' => 'Each item must have a product selected.',
            'items.*.Product_ID.exists' => 'One or more selected products do not exist.',
            'items.*.quantity.required' => 'Each item must have a quantity specified.',
            'items.*.quantity.integer' => 'Item quantity must be a whole number.',
            'items.*.quantity.min' => 'Item quantity must be at least 1.',
            'items.*.unit_price.required' => 'Each item must have a unit price.',
            'items.*.unit_price.numeric' => 'Unit price must be a number.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
            'Supplier_ID.required' => 'Supplier is required for incoming orders.',
            'Supplier_ID.exists' => 'Selected supplier does not exist.',
            'expected_delivery.required' => 'Expected delivery date is required for incoming orders.',
            'expected_delivery.date' => 'Expected delivery date must be a valid date.',
            'expected_delivery.after_or_equal' => 'Expected delivery date cannot be in the past.',
            'Distributor_ID.required' => 'Distributor is required for outgoing orders.',
            'Distributor_ID.exists' => 'Selected distributor does not exist.',
            'Shipping_Date.required' => 'Shipping date is required for outgoing orders.',
            'Shipping_Date.date' => 'Shipping date must be a valid date.',
            'Shipping_Date.after_or_equal' => 'Shipping date cannot be in the past.',
            'tracking_number.max' => 'Tracking number cannot exceed 50 characters.',
            'carrier.max' => 'Carrier name cannot exceed 50 characters.',
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            DB::transaction(function () use ($validatedData) {
                $address = Address::firstOrCreate([
                    'street_address' => $validatedData['street_address'],
                    'city' => $validatedData['city'],
                    'state_province' => $validatedData['state_province'],
                    'postal_code' => $validatedData['postal_code'],
                    'country' => $validatedData['country'],
                ]);

                $orderGrandTotal = 0;

                // Create the main Order record
                $order = Order::create([
                    'priority' => $validatedData['priority'],
                    'Shipping_Date' => $validatedData['Shipping_Date'] ?? null, // Will be null for incoming
                    'notes' => $validatedData['notes'],
                    'shipping_address_ID' => $address->address_ID,
                    'Status' => 'pending',
                    'total_amount' => 0, // Placeholder, updated after calculating items
                    // No 'order_type' column on the 'orders' table itself
                ]);

                $defaultSubsectionId = 5; // Consider making this configurable or dynamic

                foreach ($validatedData['items'] as $itemData) {
                    $product = Product::find($itemData['Product_ID']);
                    if (!$product) {
                        // This should ideally be caught by the 'exists' rule,
                        // but it's good to have a fallback for race conditions or direct DB manipulation.
                        throw new Exception('Product not found for ID: ' . $itemData['Product_ID']);
                    }

                    $itemQuantity = $itemData['quantity'];
                    $itemUnitPrice = $itemData['unit_price'];
                    $itemTotalPrice = $itemQuantity * $itemUnitPrice;
                    $orderGrandTotal += $itemTotalPrice;

                    // Get current stock for the specific product in the default subsection
                    $currentProductStock = Stock::where('Product_ID', $itemData['Product_ID'])
                        ->where('Sub_section_ID', $defaultSubsectionId)
                        ->sum('Quantity'); // Sum in case of multiple stock records (though unlikely for same subsection)

                    if ($validatedData['order_type'] === 'incoming') {
                        // Create record in 'incoming_orders' table (which represents an incoming line item)
                        IncomingOrder::create([
                            'Order_ID' => $order->Order_ID,
                            'Supplier_ID' => $validatedData['Supplier_ID'],
                            'Product_ID' => $itemData['Product_ID'],
                            'expected_delivery' => $validatedData['expected_delivery'],
                            'quantity_ordered' => $itemQuantity,
                            'quantity_received' => $itemQuantity, // Assuming full receipt on creation
                            'unit_price' => $itemUnitPrice,
                            'total_price' => $itemTotalPrice,
                        ]);

                        // Update or create stock for incoming order
                        $stock = Stock::firstOrNew([
                            'Product_ID' => $itemData['Product_ID'],
                            'Sub_section_ID' => $defaultSubsectionId,
                        ]);
                        if (!$stock->exists) {
                            $stock->Quantity = 0;
                        }
                        $stock->Quantity += $itemQuantity;
                        $stock->last_checked = now();
                        $stock->save();

                        StockMovement::create([
                            'Product_ID' => $itemData['Product_ID'],
                            'Sub_section_ID' => $defaultSubsectionId,
                            'change_quantity' => $itemQuantity,
                            'old_quantity' => $currentProductStock,
                            'new_quantity' => $currentProductStock + $itemQuantity,
                            'reason' => 'Incoming Order Receipt',
                            'movement_type' => 'add',
                            'movement_date' => now(),
                            'notes' => 'Received via Order ' . $order->Order_ID,
                        ]);
                    } elseif ($validatedData['order_type'] === 'outgoing') {
                        // Check stock for outgoing order
                        $specificStockRecord = Stock::where('Product_ID', $itemData['Product_ID'])
                            ->where('Sub_section_ID', $defaultSubsectionId)
                            ->first();

                        if (!$specificStockRecord || $specificStockRecord->Quantity < $itemQuantity) {
                            // Custom error for insufficient stock
                            throw new Exception('Insufficient stock for product "' . $product->Product_Name . '". Available: ' . ($specificStockRecord->Quantity ?? 0) . ', Requested: ' . $itemQuantity);
                        }

                        // Create record in 'outgoing_orders' table (which represents an outgoing line item)
                        OutgoingOrder::create([
                            'Order_ID' => $order->Order_ID,
                            'Product_ID' => $itemData['Product_ID'],
                            'Distribution_ID' => $validatedData['Distributor_ID'],
                            'quantity_shipped' => $itemQuantity,
                            'unit_price' => $itemUnitPrice,
                            'total_price' => $itemTotalPrice,
                            'tracking_number' => $validatedData['tracking_number'] ?? null,
                            'carrier' => $validatedData['carrier'] ?? null,
                        ]);

                        $oldStockQuantity = $specificStockRecord->Quantity;
                        $specificStockRecord->Quantity -= $itemQuantity;
                        $specificStockRecord->last_checked = now();
                        $specificStockRecord->save();

                        StockMovement::create([
                            'Product_ID' => $itemData['Product_ID'],
                            'Sub_section_ID' => $defaultSubsectionId,
                            'change_quantity' => -$itemQuantity,
                            'old_quantity' => $oldStockQuantity,
                            'new_quantity' => $specificStockRecord->Quantity,
                            'reason' => 'Outgoing Order Shipment',
                            'movement_type' => 'remove',
                            'movement_date' => now(),
                            'notes' => 'Shipped via Order ' . $order->Order_ID,
                        ]);
                    }
                }

                $order->total_amount = $orderGrandTotal;
                $order->save();

                // Initial Order Tracking entry
                $trackingData = [
                    'status_change' => 'created',
                    'Timestamp' => now(),
                    'Tracking_Comments' => 'Order created with initial status: pending.',
                    'Order_ID' => $order->Order_ID,
                ];
                if ($validatedData['order_type'] === 'incoming') {
                    $trackingData['Supplier_ID'] = $validatedData['Supplier_ID'];
                } elseif ($validatedData['order_type'] === 'outgoing') {
                    $trackingData['Distributor_ID'] = $validatedData['Distributor_ID'];
                }
                OrderTracking::create($trackingData);
            });

            return redirect()->route('orders.index')->with('success', 'Order created successfully!');
        } catch (Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage(), ['exception' => $e]);
            // If the exception message contains "Insufficient stock", pass it directly.
            // Otherwise, use a generic error message.
            if (str_contains($e->getMessage(), 'Insufficient stock')) {
                 return back()->withInput()->withErrors(['error' => $e->getMessage()]);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to create order. Please check your inputs.']);
        }
    }

    public function destroy(Order $order)
    {
        if ($order->Status == 'processing' || $order->Status == 'shipped') {
            return back()->withErrors(['error' => 'Orders in "processing" or "shipped" status cannot be deleted.']);
        }

        try {
            DB::transaction(function () use ($order) {
                // The 'onDelete('cascade')' in your migration means the database will handle
                // deleting related `incoming_orders`, `outgoing_orders`, and `order_trackings`
                // records when the main `orders` record is deleted.
                // So, explicit deletion calls for these are often not strictly necessary if CASCADE is properly set up.
                // However, it doesn't hurt to be explicit or if you need to perform additional logic.

                // If you *didn't* have cascade deletes, you would do:
                // $order->incomingOrderItems()->delete();
                // $order->outgoingOrderItems()->delete();
                // $order->tracking()->delete();

                $order->delete(); // This will trigger cascade deletes if defined in migration

            });

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Order deletion failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'Failed to delete order: ' . $e->getMessage()]);
        }
    }

    public function cancel(Order $order)
    {
        if ($order->Status == 'delivered' || $order->Status == 'cancelled') {
            return redirect()->back()->with('error', 'Cannot cancel an order that is already ' . $order->Status . '.');
        }

        $order->Status = 'cancelled';
        $order->save();

        // Add tracking entry for cancellation
        $trackingData = [
            'status_change' => 'cancelled',
            'Timestamp' => now(),
            'Tracking_Comments' => 'Order cancelled by user.',
            'Order_ID' => $order->Order_ID,
        ];
        // Populate Supplier_ID/Distributor_ID for tracking if available
        if ($order->order_type === 'incoming' && $order->supplier) {
            $trackingData['Supplier_ID'] = $order->supplier->Supplier_ID;
        } elseif ($order->order_type === 'outgoing' && $order->distributor) {
            $trackingData['Distributor_ID'] = $order->distributor->Distributor_ID;
        }
        OrderTracking::create($trackingData);

        return redirect()->back()->with('success', 'Order ' . str_pad($order->Order_ID, 5, '0', STR_PAD_LEFT) . ' has been cancelled.');
    }
}
