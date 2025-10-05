<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Needed for DB::raw()

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create tables in order of dependency
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('address_ID');
            $table->string('street_address', 100);
            $table->string('city', 50);
            $table->string('state_province', 50)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 50);
            $table->timestamps();
        });

        Schema::create('contact_numbers', function (Blueprint $table) {
            $table->increments('contact_ID');
            $table->enum('contact_type', ['phone', 'mobile', 'fax', 'email']);
            $table->string('contact_value', 50);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('warehouse_ID');
            $table->unsignedInteger('address_ID');
            $table->string('Name', 100);
            $table->integer('Max_Capacity');
            $table->enum('operational_status', ['active', 'maintenance', 'closed'])->default('active');
            $table->timestamps();
            $table->foreign('address_ID')->references('address_ID')->on('addresses')->onDelete('cascade');
        });

        Schema::create('warehouse_contacts', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_ID');
            $table->unsignedInteger('contact_ID');
            $table->timestamps();
            $table->primary(['warehouse_ID', 'contact_ID']);
            $table->foreign('warehouse_ID')->references('warehouse_ID')->on('warehouses')->onDelete('cascade');
            $table->foreign('contact_ID')->references('contact_ID')->on('contact_numbers')->onDelete('cascade');
        });

        Schema::create('warehouse_sections', function (Blueprint $table) {
            $table->increments('section_ID');
            $table->integer('s_Capacity');
            $table->string('section_name', 50)->nullable();
            $table->enum('temperature_zone', ['ambient', 'chilled', 'frozen', 'controlled'])->nullable();
            $table->timestamps();
        });

        Schema::create('includes', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_ID');
            $table->unsignedInteger('section_ID');
            $table->timestamps();
            $table->primary(['warehouse_ID', 'section_ID']);
            $table->foreign('warehouse_ID')->references('warehouse_ID')->on('warehouses')->onDelete('cascade');
            $table->foreign('section_ID')->references('section_ID')->on('warehouse_sections')->onDelete('cascade');
        });

        Schema::create('subsections', function (Blueprint $table) {
            $table->increments('Sub_Section_ID');
            $table->string('sub_Hype', 100)->nullable();
            $table->integer('Sub_Capacity');
            $table->string('subsection_name', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('divides', function (Blueprint $table) {
            $table->unsignedInteger('Sub_Section_ID');
            $table->unsignedInteger('section_ID');
            $table->timestamps();
            $table->primary(['Sub_Section_ID', 'section_ID']);
            $table->foreign('Sub_Section_ID')->references('Sub_Section_ID')->on('subsections')->onDelete('cascade');
            $table->foreign('section_ID')->references('section_ID')->on('warehouse_sections')->onDelete('cascade');
        });

        Schema::create('cold_storages', function (Blueprint $table) {
            $table->unsignedInteger('Sub_section_ID')->primary();
            $table->string('Freezer_Sec_Assigned', 50)->nullable();
            $table->decimal('min_temperature', 5, 2)->nullable();
            $table->decimal('max_temperature', 5, 2)->nullable();
            $table->timestamps();
            $table->foreign('Sub_section_ID')->references('Sub_Section_ID')->on('subsections')->onDelete('cascade');
        });

        Schema::create('bulk_storages', function (Blueprint $table) {
            $table->unsignedInteger('Sub_section_ID')->primary();
            $table->string('Weight_Measurement', 50)->nullable();
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('Sub_section_ID')->references('Sub_Section_ID')->on('subsections')->onDelete('cascade');
        });

        Schema::create('hazardous_materials', function (Blueprint $table) {
            $table->unsignedInteger('Sub_section_ID')->primary();
            $table->string('Hazardous_Class', 50);
            $table->string('safety_level', 50)->nullable();
            $table->timestamps();
            $table->foreign('Sub_section_ID')->references('Sub_Section_ID')->on('subsections')->onDelete('cascade');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('Product_ID');
            $table->string('Product_Name', 100);
            $table->date('Product_Date')->nullable();
            $table->text('description')->nullable();
            $table->string('barcode', 50)->unique()->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->string('status', 20)->default('active'); // Added from ALTER
            $table->string('image')->nullable(); // New: Image URL or path
            $table->decimal('price', 10, 2)->nullable(); // New: Price of the product
            $table->timestamps();
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->increments('Type_ID');
            $table->string('Type_Name', 100);
            $table->unsignedInteger('Section_ID')->nullable();
            $table->text('storage_requirements')->nullable();
            $table->timestamps();
            $table->foreign('Section_ID')->references('section_ID')->on('warehouse_sections')->onDelete('set null');
        });

        Schema::create('belongs_tos', function (Blueprint $table) {
            $table->unsignedInteger('Product_ID');
            $table->unsignedInteger('Type_ID');
            $table->timestamps();
            $table->primary(['Product_ID', 'Type_ID']);
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
            $table->foreign('Type_ID')->references('Type_ID')->on('product_types')->onDelete('cascade');
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('Stock_ID');
            $table->integer('Quantity')->default(0);
            $table->date('Manufactured_Date')->nullable();
            $table->date('Use_By_Date')->nullable();
            $table->string('batch_number', 50)->nullable();
            $table->unsignedInteger('Product_ID');
            $table->unsignedInteger('Sub_section_ID')->nullable();
            $table->timestamp('last_checked')->nullable();
            $table->timestamps();
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
            $table->foreign('Sub_section_ID')->references('Sub_Section_ID')->on('subsections')->onDelete('set null');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('Order_ID');
            $table->unsignedInteger('shipping_address_ID');
            $table->date('Shipping_Date')->nullable();
            $table->enum('Status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable(); // New: Total amount of the order
            $table->timestamp('processed_at')->nullable(); // New: Timestamp when order was processed
            $table->timestamp('shipped_at')->nullable();  // New: Timestamp when order was shipped
            $table->timestamp('delivered_at')->nullable(); // New: Timestamp when order was delivered
            $table->timestamps();
            $table->foreign('shipping_address_ID')->references('address_ID')->on('addresses')->onDelete('cascade');
        });

        Schema::create('order_contacts', function (Blueprint $table) {
            $table->unsignedInteger('Order_ID');
            $table->unsignedInteger('contact_ID');
            $table->timestamps();
            $table->primary(['Order_ID', 'contact_ID']);
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');
            $table->foreign('contact_ID')->references('contact_ID')->on('contact_numbers')->onDelete('cascade');
        });

        Schema::create('distributors', function (Blueprint $table) {
            $table->increments('Distributor_ID');
            $table->string('Distributor_Name', 100);
            $table->unsignedInteger('address_ID');
            $table->string('Distributing_Location', 100)->nullable();
            $table->string('tax_id', 50)->nullable();
            $table->string('account_manager', 100)->nullable();
            $table->timestamps();
            $table->foreign('address_ID')->references('address_ID')->on('addresses')->onDelete('cascade');
        });

        Schema::create('distributor_contacts', function (Blueprint $table) {
            $table->unsignedInteger('Distributor_ID');
            $table->unsignedInteger('contact_ID');
            $table->timestamps();
            $table->primary(['Distributor_ID', 'contact_ID']);
            $table->foreign('Distributor_ID')->references('Distributor_ID')->on('distributors')->onDelete('cascade');
            $table->foreign('contact_ID')->references('contact_ID')->on('contact_numbers')->onDelete('cascade');
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('Supplier_ID');
            $table->unsignedInteger('address_ID');
            $table->string('Supplier_Name', 100);
            $table->decimal('reliability_rating', 3, 2)->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->timestamps();
            $table->foreign('address_ID')->references('address_ID')->on('addresses')->onDelete('cascade');
        });

        Schema::create('supplier_contacts', function (Blueprint $table) {
            $table->unsignedInteger('Supplier_ID');
            $table->unsignedInteger('contact_ID');
            $table->timestamps();
            $table->primary(['Supplier_ID', 'contact_ID']);
            $table->foreign('Supplier_ID')->references('Supplier_ID')->on('suppliers')->onDelete('cascade');
            $table->foreign('contact_ID')->references('contact_ID')->on('contact_numbers')->onDelete('cascade');
        });

        Schema::create('incoming_orders', function (Blueprint $table) {
            $table->unsignedInteger('Supplier_ID');
            $table->unsignedInteger('Order_ID');
            $table->unsignedInteger('Product_ID');
            $table->date('expected_delivery')->nullable();
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable(); // New: Unit price for incoming product
            $table->decimal('total_price', 12, 2)->nullable(); // New: Total price for incoming line item
            $table->timestamps();
            $table->primary(['Supplier_ID', 'Order_ID', 'Product_ID']);
            $table->foreign('Supplier_ID')->references('Supplier_ID')->on('suppliers')->onDelete('cascade');
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
        });

        Schema::create('outgoing_orders', function (Blueprint $table) {
            $table->unsignedInteger('Order_ID');
            $table->unsignedInteger('Product_ID');
            $table->unsignedInteger('Distribution_ID');
            $table->integer('quantity_shipped');
            $table->string('tracking_number', 50)->nullable();
            $table->string('carrier', 50)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable(); // New: Unit price for outgoing product
            $table->decimal('total_price', 12, 2)->nullable(); // New: Total price for outgoing line item
            $table->timestamps();
            $table->primary(['Order_ID', 'Product_ID']);
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
            $table->foreign('Distribution_ID')->references('Distributor_ID')->on('distributors')->onDelete('cascade');
        });

        Schema::create('order_trackings', function (Blueprint $table) {
            $table->increments('Tracking_ID');
            $table->text('Tracking_Comments')->nullable();
            $table->unsignedInteger('Supplier_ID')->nullable();
            $table->unsignedInteger('Distributor_ID')->nullable();
            $table->unsignedInteger('Order_ID');
            $table->dateTime('Timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status_change', ['created', 'processed', 'shipped', 'delivered', 'returned', 'cancelled'])->nullable(); // Added 'cancelled'
            $table->timestamps();
            $table->foreign('Supplier_ID')->references('Supplier_ID')->on('suppliers')->onDelete('set null');
            $table->foreign('Distributor_ID')->references('Distributor_ID')->on('distributors')->onDelete('set null');
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->increments('Emp_ID');
            $table->string('F_Name', 50);
            $table->string('L_Name', 50);
            $table->date('DoB')->nullable();
            $table->unsignedInteger('address_ID');
            $table->date('hire_date');
            $table->date('termination_date')->nullable(); // Added termination_date column
            $table->string('position', 50)->nullable();
            $table->string('department', 50)->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->timestamps();
            $table->foreign('address_ID')->references('address_ID')->on('addresses')->onDelete('cascade');
        });

        Schema::create('employee_contacts', function (Blueprint $table) {
            $table->unsignedInteger('Emp_ID');
            $table->unsignedInteger('contact_ID');
            $table->timestamps();
            $table->primary(['Emp_ID', 'contact_ID']);
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('cascade');
            $table->foreign('contact_ID')->references('contact_ID')->on('contact_numbers')->onDelete('cascade');
        });

        Schema::create('supervisors', function (Blueprint $table) {
            $table->unsignedInteger('Emp_ID')->primary();
            $table->integer('team_size')->nullable();
            $table->string('department', 50)->nullable();
            $table->timestamps();
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('cascade');
        });

        Schema::create('order_processors', function (Blueprint $table) {
            $table->unsignedInteger('Emp_ID')->primary();
            $table->decimal('Avg_time_Processing', 10, 2)->nullable();
            $table->decimal('Accuracy_Rate', 5, 2)->nullable();
            $table->integer('orders_processed')->default(0);
            $table->timestamps();
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('cascade');
        });

        Schema::create('inventory_clerks', function (Blueprint $table) {
            $table->unsignedInteger('Emp_ID')->primary();
            $table->boolean('Forklift_Certification')->default(false);
            $table->integer('Items_Processed_PerDay')->nullable();
            $table->decimal('inventory_accuracy', 5, 2)->nullable();
            $table->timestamps();
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('cascade');
        });

        Schema::create('shipping_clerks', function (Blueprint $table) {
            $table->unsignedInteger('Emp_ID')->primary();
            $table->decimal('Error_Rate', 5, 2)->nullable();
            $table->integer('Packages_PerDay')->nullable();
            $table->decimal('shipping_accuracy', 5, 2)->nullable();
            $table->timestamps();
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('cascade');
        });

        Schema::create('supervisions', function (Blueprint $table) {
            $table->unsignedInteger('Emp_ID');
            $table->unsignedInteger('Supervisor_ID');
            $table->enum('relationship_type', ['direct', 'temporary', 'departmental'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->primary(['Emp_ID', 'Supervisor_ID']);
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('cascade');
            $table->foreign('Supervisor_ID')->references('Emp_ID')->on('supervisors')->onDelete('cascade');
        });

        Schema::create('order_processings', function (Blueprint $table) {
            $table->increments('processing_ID');
            $table->unsignedInteger('Emp_ID');
            $table->unsignedInteger('Order_ID');
            $table->dateTime('Processing_Start');
            $table->dateTime('Processing_End')->nullable();
            $table->enum('Status', ['assigned', 'in-progress', 'completed', 'cancelled']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('Emp_ID')->references('Emp_ID')->on('order_processors')->onDelete('cascade');
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->increments('movement_ID');
            $table->unsignedInteger('stock_ID')->nullable();
            $table->unsignedInteger('Product_ID');
            $table->unsignedInteger('Sub_section_ID');
            $table->unsignedInteger('Emp_ID')->nullable();
            $table->integer('change_quantity');
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->string('reason', 100);
            $table->text('notes')->nullable();
            $table->enum('movement_type', ['add', 'remove', 'set', 'transfer_in', 'transfer_out']);
            $table->dateTime('movement_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
            $table->foreign('stock_ID')->references('Stock_ID')->on('stocks')->onDelete('set null');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
            $table->foreign('Sub_section_ID')->references('Sub_Section_ID')->on('subsections')->onDelete('cascade');
            $table->foreign('Emp_ID')->references('Emp_ID')->on('employees')->onDelete('set null');
        });

        // Add indexes
        Schema::table('warehouses', function (Blueprint $table) {
            $table->index('Name', 'idx_warehouse_name');
            $table->index('operational_status', 'idx_warehouse_status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('Product_Name', 'idx_product_name');
            $table->index('barcode', 'idx_product_barcode');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('Status', 'idx_order_status');
            $table->index('priority', 'idx_order_priority');
            $table->index('Shipping_Date', 'idx_order_date');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index(['L_Name', 'F_Name'], 'idx_employee_name');
            $table->index('department', 'idx_employee_department');
            // Added index for termination_date
            $table->index('termination_date', 'idx_employee_termination_date');
        });

        Schema::table('order_processings', function (Blueprint $table) {
            $table->index('Status', 'idx_order_processing_status');
            $table->index('Emp_ID', 'idx_order_processing_employee');
            $table->index('Processing_Start', 'idx_order_processing_date');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->index('Product_ID', 'idx_stock_product');
            $table->index('Use_By_Date', 'idx_stock_expiry');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('Product_ID', 'idx_stock_movement_product');
            $table->index('Sub_section_ID', 'idx_stock_movement_location');
            $table->index('Emp_ID', 'idx_stock_movement_employee');
            $table->index('movement_date', 'idx_stock_movement_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes first
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropIndex('idx_warehouse_name');
            $table->dropIndex('idx_warehouse_status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_product_name');
            $table->dropIndex('idx_product_barcode');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_order_status');
            $table->dropIndex('idx_order_priority');
            $table->dropIndex('idx_order_date');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_employee_name');
            $table->dropIndex('idx_employee_department');
            $table->dropIndex('idx_employee_termination_date'); // Drop index for termination_date
        });

        Schema::table('order_processings', function (Blueprint $table) {
            $table->dropIndex('idx_order_processing_status');
            $table->dropIndex('idx_order_processing_employee');
            $table->dropIndex('idx_order_processing_date');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex('idx_stock_product');
            $table->dropIndex('idx_stock_expiry');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('idx_stock_movement_product');
            $table->dropIndex('idx_stock_movement_location');
            $table->dropIndex('idx_stock_movement_employee');
            $table->dropIndex('idx_stock_movement_date');
        });

        // Drop the altered columns
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('image');
            $table->dropColumn('price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
            $table->dropColumn('processed_at');
            $table->dropColumn('shipped_at');
            $table->dropColumn('delivered_at');
        });

        Schema::table('incoming_orders', function (Blueprint $table) {
            $table->dropColumn('unit_price');
            $table->dropColumn('total_price');
        });

        Schema::table('outgoing_orders', function (Blueprint $table) {
            $table->dropColumn('unit_price');
            $table->dropColumn('total_price');
        });

        // Drop the new column from the 'employees' table
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('termination_date');
        });

        // Drop tables in reverse order of dependency
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('order_processings');
        Schema::dropIfExists('supervisions');
        Schema::dropIfExists('shipping_clerks');
        Schema::dropIfExists('inventory_clerks');
        Schema::dropIfExists('order_processors');
        Schema::dropIfExists('supervisors');
        Schema::dropIfExists('employee_contacts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('order_trackings');
        Schema::dropIfExists('outgoing_orders');
        Schema::dropIfExists('incoming_orders');
        Schema::dropIfExists('supplier_contacts');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('distributor_contacts');
        Schema::dropIfExists('distributors');
        Schema::dropIfExists('order_contacts');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('belongs_tos');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('products');
        Schema::dropIfExists('hazardous_materials');
        Schema::dropIfExists('bulk_storages');
        Schema::dropIfExists('cold_storages');
        Schema::dropIfExists('divides');
        Schema::dropIfExists('subsections');
        Schema::dropIfExists('includes');
        Schema::dropIfExists('warehouse_sections');
        Schema::dropIfExists('warehouse_contacts');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('contact_numbers');
        Schema::dropIfExists('addresses');
    }
};
