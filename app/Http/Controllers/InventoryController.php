<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Subsection;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
   public function index(Request $request)
    {
        // Your existing inventory filtering logic
        $query = Stock::with(['product', 'subsection.warehouseSections.warehouses']);

        if ($request->has('warehouses')) {
            $warehouseId = $request->input('warehouses');
            $query->whereHas('subsection.warehouseSections.warehouses', function ($q) use ($warehouseId) {
                $q->where('warehouse_ID', $warehouseId);
            });
        }

       $inventory = Stock::with(['product', 'subsection.warehouseSections.warehouses'])
                      // ... your where clauses if any ...
                      ->get(); // Or paginate: $query->paginate(10);

        // Data for the Adjust Inventory Modal
        $products = Product::all(); // Fetch all products for the modal dropdown

        // --- NEW/UPDATED LINES BELOW ---
        $inventory = $query->paginate(10);

        // Fetch ALL warehouses for the main filter dropdown at the top of the page
        // The error indicates this variable ($warehouses) is missing.
        $warehouses = Warehouse::all(); // Or Warehouse::orderBy('Name')->get();

        // Fetch ALL warehouses with their sections and subsections for the 'Location' dropdown in the modal
        // This was previously suggested and is crucial for the modal's hierarchical dropdown.
        $allWarehouses = Warehouse::with('sections.subsections')->get();

        // Pass all necessary variables to the view
        return view('inventory.index', compact('inventory', 'products', 'warehouses', 'allWarehouses'));
    }
   public function adjust(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'product_id' => 'required|exists:Products,Product_ID', // Validate product exists
            'subsection_id' => 'required|exists:Subsections,Sub_Section_ID', // Validate subsection exists
            'adjustment_type' => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:100',
            'notes' => 'nullable|string',
            // 'emp_id' => 'nullable|exists:Employee,Emp_ID', // Include if you're sending emp_id from frontend
        ]);

        $product_id = $validatedData['product_id'];
        $subsection_id = $validatedData['subsection_id'];
        $adjustment_type = $validatedData['adjustment_type'];
        $quantity = $validatedData['quantity'];
        $reason = $validatedData['reason'];
        $notes = $validatedData['notes'];

        // Fetch existing stock or initialize
        $stock = Stock::where('Product_ID', $product_id)
                      ->where('Sub_section_ID', $subsection_id)
                      ->first();

        $originalQuantity = $stock ? $stock->Quantity : 0;
        $newQuantity = $originalQuantity;
        $change = 0; // Initialize change

        if ($adjustment_type == 'add') {
            $newQuantity = $originalQuantity + $quantity;
            $change = $quantity;
        } elseif ($adjustment_type == 'remove') {
            // Ensure you don't go below zero unless business logic allows
            $newQuantity = max(0, $originalQuantity - $quantity);
            $change = -$quantity; // Negative for removal
        } elseif ($adjustment_type == 'set') {
            $newQuantity = $quantity;
            $change = $newQuantity - $originalQuantity;
        }

        if ($stock) {
            $stock->Quantity = $newQuantity;
            $stock->save();
        } else {
            // If stock doesn't exist for 'add' or 'set' type, create it
            if ($adjustment_type == 'add' || $adjustment_type == 'set') {
                $stock = Stock::create([
                    'Product_ID' => $product_id,
                    'Sub_section_ID' => $subsection_id,
                    'Quantity' => $newQuantity,
                    'Manufactured_Date' => null, // Or set a default/get from request
                    'Use_By_Date' => null,       // Or set a default/get from request
                    'batch_number' => null,      // Or set a default/get from request
                ]);
            } else {
                // Handle cases where 'remove' or other types are applied to non-existent stock
                return back()->withErrors(['error' => 'Cannot remove from non-existent stock.']);
            }
        }

        // Record the stock movement for auditing
        StockMovement::create([
            'stock_ID' => $stock->Stock_ID, // Use the correct PascalCase/CamelCase name from DB
            'Product_ID' => $product_id,    // Use the correct PascalCase/CamelCase name from DB
            'Sub_section_ID' => $subsection_id, // Use the correct PascalCase/CamelCase name from DB
            // Assuming your user is an Employee. Adjust if your user model is different.
            'change_quantity' => $change,
            'old_quantity' => $originalQuantity,
            'new_quantity' => $newQuantity,
            'reason' => $reason,
            'notes' => $notes,
            'movement_type' => $adjustment_type,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory adjusted successfully!');
    }
    public function Movements(Request $request)
{
    $perPage = $request->input('per_page', 10); // default to 10
    $movements = StockMovement::with([
        'stock.product',
        'stock.subsection.warehouseSections.warehouses',
        'employee'
    ])
    ->orderBy('created_at', 'desc')
   ->get();

    return view('inventory.movements', compact('movements'));
}
public function login(){

    return view('auth.login');
}
public function signup(){

    return view('auth.register');
}


}
