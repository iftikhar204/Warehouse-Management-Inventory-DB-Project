<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Subsection;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'types',
            'stocks.subsection.warehouseSections.warehouses'
        ])->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $productTypes = ProductType::all();

        $allWarehouses = Warehouse::with('sections.subsections')->get();

        return view('products.create', compact('productTypes', 'allWarehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Product_Name' => 'required|string|max:255',
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'Type_Name' => 'required|string|max:100',
            'Product_Date' => 'required|date',
            'weight' => 'required|numeric',
            'dimensions' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048',
            'description' => 'required|string',
            'status' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'batch_number' => 'required|string|max:255',
            'Sub_section_ID' => 'required|exists:subsections,Sub_Section_ID',
            'Manufactured_Date' => 'required|date',
            'Use_By_Date' => 'required|date',
        ], [
            'Product_Name.required' => 'The product name is required.',
            'Product_Name.string' => 'The product name must be a string.',
            'Product_Name.max' => 'The product name cannot exceed 255 characters.',

            'barcode.required' => 'The barcode is required.',
            'barcode.unique' => 'This barcode is already in use. Please generate a new one or use a different one.',
            'barcode.max' => 'The barcode cannot exceed 255 characters.',

            'Type_Name.required' => 'Please specify a product type.',
            'Type_Name.string' => 'The product type must be a string.',
            'Type_Name.max' => 'The product type name cannot exceed 100 characters.',

            'Product_Date.required' => 'The product date is required.',
            'Product_Date.date' => 'The product date must be a valid date format.',

            'weight.required' => 'The weight is required.',
            'weight.numeric' => 'The weight must be a number.',

            'dimensions.required' => 'The dimensions are required.',
            'dimensions.string' => 'The dimensions must be a string.',
            'dimensions.max' => 'The dimensions cannot exceed 255 characters.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image size cannot exceed 2MB.',

            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',

            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.max' => 'The status cannot exceed 50 characters.',

            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price cannot be negative.',

            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity cannot be negative.',

            'batch_number.required' => 'The batch number is required.',
            'batch_number.string' => 'The batch number must be a string.',
            'batch_number.max' => 'The batch number cannot exceed 255 characters.',

            'Sub_section_ID.required' => 'The subsection is required.',
            'Sub_section_ID.exists' => 'The selected subsection does not exist.',

            'Manufactured_Date.required' => 'The manufactured date is required.',
            'Manufactured_Date.date' => 'The manufactured date must be a valid date format.',
            'Use_By_Date.required' => 'The use-by date is required.',
            'Use_By_Date.date' => 'The use-by date must be a valid date format.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product_images', 'public');
        }

        $type = ProductType::firstOrCreate([
            'Type_Name' => $validated['Type_Name']
        ]);

        $product = Product::create([
            'Product_Name' => $validated['Product_Name'],
            'barcode' => $validated['barcode'],
            'Product_Date' => $validated['Product_Date'],
            'weight' => $validated['weight'],
            'dimensions' => $validated['dimensions'],
            'image' => $validated['image'] ?? null,
            'description' => $validated['description'],
            'status' => $validated['status'],
            'price' => $validated['price'],
        ]);

        DB::table('belongs_tos')->insert([
            'Product_ID' => $product->Product_ID,
            'Type_ID' => $type->Type_ID,
        ]);

        Stock::create([
            'Product_ID' => $product->Product_ID,
            'Quantity' => $validated['quantity'],
            'batch_number' => $validated['batch_number'],
            'Sub_section_ID' => $validated['Sub_section_ID'],
            'Manufactured_Date' => $validated['Manufactured_Date'],
            'Use_By_Date' => $validated['Use_By_Date'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


    public function edit($id)
    {
        $product = Product::with([
            'types',
            'stocks.subsection.warehouseSections.warehouses'
        ])->findOrFail($id);

        $productTypes = ProductType::all();
        $allWarehouses = \App\Models\Warehouse::with('sections.subsections')->get();

        return view('products.edit', compact('product', 'productTypes', 'allWarehouses'));
    }

   public function update(Request $request, Product $product)
{
    // Validate product-level data
    $productValidatedData = $request->validate([
        'Product_Name' => 'required|string|max:255',
        'barcode' => [
            'required',
            Rule::unique('products')->ignore($product->Product_ID, 'Product_ID'),
        ],
        'Type_Name' => 'required|string|max:100',
        'Product_Date' => 'required|date',
        'weight' => 'required|numeric',
        'dimensions' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|string|max:50',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png|max:2048',
    ], [
        'Product_Name.required' => 'The product name is required.',
        'Product_Name.string' => 'The product name must be a string.',
        'Product_Name.max' => 'The product name cannot exceed 255 characters.',
        'barcode.required' => 'The barcode is required.',
        'barcode.unique' => 'This barcode is already in use by another product. Please use a different one.',
        'Type_Name.required' => 'Please specify a product type.',
        'Type_Name.string' => 'The product type must be a string.',
        'Type_Name.max' => 'The product type name cannot exceed 100 characters.',
        'Product_Date.required' => 'The product date is required.',
        'Product_Date.date' => 'The product date must be a valid date format.',
        'weight.required' => 'The weight is required.',
        'weight.numeric' => 'The weight must be a number.',
        'dimensions.required' => 'The dimensions are required.',
        'dimensions.string' => 'The dimensions must be a string.',
        'dimensions.max' => 'The dimensions cannot exceed 255 characters.',
        'description.required' => 'The description is required.',
        'description.string' => 'The description must be a string.',
        'status.required' => 'The status is required.',
        'status.string' => 'The status must be a string.',
        'status.max' => 'The status cannot exceed 50 characters.',
        'price.required' => 'The price is required.',
        'price.numeric' => 'The price must be a number.',
        'price.min' => 'The price cannot be negative.',
        'image.image' => 'The uploaded file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        'image.max' => 'The image size cannot exceed 2MB.',
    ]);

    // Handle product image update
    if ($request->hasFile('image')) {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $productValidatedData['image'] = $request->file('image')->store('product_images', 'public');
    }

    // Update product details
    $product->update($productValidatedData);

    // Update or create product type association
    $type = ProductType::firstOrCreate([
        'Type_Name' => $request->input('Type_Name') // Use $request->input to get the value
    ]);

    DB::table('belongs_tos')->updateOrInsert(
        ['Product_ID' => $product->Product_ID],
        ['Type_ID' => $type->Type_ID]
    );

    // --------------------------------------------------------------------------
    // Handle multiple stock records
    // This assumes your form will submit an array of stock data
    // e.g., 'stocks' => [['stock_id' => 1, 'quantity' => 10, ...], ['quantity' => 5, ...]]
    // --------------------------------------------------------------------------

    $request->validate([
        'stocks' => 'array',
        'stocks.*.Stock_ID' => 'nullable|exists:stocks,Stock_ID', // Existing stock ID or null for new
        'stocks.*.Quantity' => 'required|integer|min:0',
        'stocks.*.batch_number' => 'required|string|max:255',
        'stocks.*.Sub_section_ID' => 'required|exists:subsections,Sub_Section_ID',
        'stocks.*.Manufactured_Date' => 'required|date',
        'stocks.*.Use_By_Date' => 'nullable|date', // Use By Date can be nullable
    ], [
        'stocks.*.Quantity.required' => 'Quantity is required for each stock record.',
        'stocks.*.Quantity.integer' => 'Quantity must be an integer.',
        'stocks.*.Quantity.min' => 'Quantity cannot be negative.',
        'stocks.*.batch_number.required' => 'Batch number is required for each stock record.',
        'stocks.*.Sub_section_ID.required' => 'Storage Location is required for each stock record.',
        'stocks.*.Sub_section_ID.exists' => 'The selected storage location does not exist.',
        'stocks.*.Manufactured_Date.required' => 'Manufactured date is required for each stock record.',
        'stocks.*.Manufactured_Date.date' => 'Manufactured date must be a valid date.',
        'stocks.*.Use_By_Date.date' => 'Use by date must be a valid date.',
    ]);

    $existingStockIds = $product->stocks->pluck('Stock_ID')->toArray();
    $submittedStockIds = [];

    if ($request->has('stocks')) {
        foreach ($request->input('stocks') as $stockData) {
            if (isset($stockData['Stock_ID']) && $stockData['Stock_ID'] != '') {
                // Update existing stock
                Stock::findOrFail($stockData['Stock_ID'])->update([
                    'Quantity' => $stockData['Quantity'],
                    'batch_number' => $stockData['batch_number'],
                    'Sub_section_ID' => $stockData['Sub_section_ID'],
                    'Manufactured_Date' => $stockData['Manufactured_Date'],
                    'Use_By_Date' => $stockData['Use_By_Date'],
                ]);
                $submittedStockIds[] = $stockData['Stock_ID'];
            } else {
                // Create new stock entry
                Stock::create([
                    'Product_ID' => $product->Product_ID,
                    'Quantity' => $stockData['Quantity'],
                    'batch_number' => $stockData['batch_number'],
                    'Sub_section_ID' => $stockData['Sub_section_ID'],
                    'Manufactured_Date' => $stockData['Manufactured_Date'],
                    'Use_By_Date' => $stockData['Use_By_Date'],
                ]);
            }
        }
    }

    // Delete stocks that were not submitted (removed from the form)
    $stocksToDelete = array_diff($existingStockIds, $submittedStockIds);
    Stock::whereIn('Stock_ID', $stocksToDelete)->delete();

    return redirect()->route('products.index')->with('success', 'Product updated successfully!');
}

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::table('stocks')->where('Product_ID', $id)->delete();
        DB::table('incoming_orders')->where('Product_ID', $id)->delete();
        DB::table('outgoing_orders')->where('Product_ID', $id)->delete();
        DB::table('belongs_tos')->where('Product_ID', $id)->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
    public function generateBarcode()
    {
        do {
            $barcode = 'BAR' . Str::random(10);
        } while (Product::where('barcode', $barcode)->exists());

        return response()->json(['barcode' => $barcode]);
    }
}
