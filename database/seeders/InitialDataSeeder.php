<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow insertion order flexibility
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data (optional, useful for fresh seeding)
        DB::table('stock_movements')->truncate();
        DB::table('order_processings')->truncate();
        DB::table('supervisions')->truncate();
        DB::table('shipping_clerks')->truncate();
        DB::table('inventory_clerks')->truncate();
        DB::table('order_processors')->truncate();
        DB::table('supervisors')->truncate();
        DB::table('employee_contacts')->truncate();
        DB::table('employees')->truncate();
        DB::table('order_trackings')->truncate();
        DB::table('outgoing_orders')->truncate();
        DB::table('incoming_orders')->truncate();
        DB::table('supplier_contacts')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('distributor_contacts')->truncate();
        DB::table('distributors')->truncate();
        DB::table('order_contacts')->truncate();
        DB::table('orders')->truncate();
        DB::table('stocks')->truncate();
        DB::table('belongs_tos')->truncate();
        DB::table('product_types')->truncate();
        DB::table('products')->truncate();
        DB::table('hazardous_materials')->truncate();
        DB::table('bulk_storages')->truncate();
        DB::table('cold_storages')->truncate();
        DB::table('divides')->truncate();
        DB::table('subsections')->truncate();
        DB::table('includes')->truncate();
        DB::table('warehouse_sections')->truncate();
        DB::table('warehouse_contacts')->truncate();
        DB::table('warehouses')->truncate();
        DB::table('contact_numbers')->truncate();
        DB::table('addresses')->truncate();


        // Insert data in correct order of dependency
        DB::table('addresses')->insert([
            ['street_address' => '123 Main St', 'city' => 'Anytown', 'state_province' => 'CA', 'postal_code' => '90210', 'country' => 'USA'],
            ['street_address' => '456 Oak Ave', 'city' => 'Sometown', 'state_province' => 'NY', 'postal_code' => '10001', 'country' => 'USA'],
            ['street_address' => '789 Pine Ln', 'city' => 'Otherville', 'state_province' => 'TX', 'postal_code' => '75001', 'country' => 'USA'],
            ['street_address' => '101 Maple Rd', 'city' => 'Big City', 'state_province' => 'ON', 'postal_code' => 'M1M 1M1', 'country' => 'Canada'],
            ['street_address' => '22 Elm St', 'city' => 'Small Town', 'state_province' => 'BC', 'postal_code' => 'V2V 2V2', 'country' => 'Canada'],
            ['street_address' => '33 Birch Cres', 'city' => 'London', 'state_province' => 'England', 'postal_code' => 'SW1A 0AA', 'country' => 'UK'],
            ['street_address' => '44 Cedar Dr', 'city' => 'Manchester', 'state_province' => 'England', 'postal_code' => 'M1 1AA', 'country' => 'UK'],
            ['street_address' => '55 Sydney Rd', 'city' => 'Sydney', 'state_province' => 'NSW', 'postal_code' => '2000', 'country' => 'Australia'],
            ['street_address' => '66 Melbourne St', 'city' => 'Melbourne', 'state_province' => 'VIC', 'postal_code' => '3000', 'country' => 'Australia'],
            ['street_address' => '77 Berlin Allee', 'city' => 'Berlin', 'state_province' => 'Berlin', 'postal_code' => '10115', 'country' => 'Germany'],
        ]);

        DB::table('contact_numbers')->insert([
            ['contact_type' => 'phone', 'contact_value' => '555-111-2222', 'is_primary' => TRUE],
            ['contact_type' => 'email', 'contact_value' => 'info@warehouse1.com', 'is_primary' => TRUE],
            ['contact_type' => 'mobile', 'contact_value' => '555-333-4444', 'is_primary' => FALSE],
            ['contact_type' => 'phone', 'contact_value' => '555-555-6666', 'is_primary' => FALSE],
            ['contact_type' => 'email', 'contact_value' => 'support@warehouse2.com', 'is_primary' => TRUE],
            ['contact_type' => 'fax', 'contact_value' => '555-777-8888', 'is_primary' => FALSE],
            ['contact_type' => 'phone', 'contact_value' => '555-999-0000', 'is_primary' => TRUE],
            ['contact_type' => 'mobile', 'contact_value' => '555-123-4567', 'is_primary' => TRUE],
            ['contact_type' => 'email', 'contact_value' => 'sales@distributor.com', 'is_primary' => FALSE],
            ['contact_type' => 'phone', 'contact_value' => '555-987-6543', 'is_primary' => TRUE],
        ]);

        DB::table('warehouses')->insert([
            ['address_ID' => 1, 'Name' => 'Central Distribution Hub', 'Max_Capacity' => 100000, 'operational_status' => 'active'],
            ['address_ID' => 2, 'Name' => 'North Regional Warehouse', 'Max_Capacity' => 75000, 'operational_status' => 'active'],
            ['address_ID' => 3, 'Name' => 'South Logistics Center', 'Max_Capacity' => 50000, 'operational_status' => 'maintenance'],
            ['address_ID' => 4, 'Name' => 'East Coast Storage', 'Max_Capacity' => 120000, 'operational_status' => 'active'],
            ['address_ID' => 5, 'Name' => 'West Coast Fulfillment', 'Max_Capacity' => 90000, 'operational_status' => 'active'],
            ['address_ID' => 6, 'Name' => 'European Hub', 'Max_Capacity' => 80000, 'operational_status' => 'active'],
            ['address_ID' => 7, 'Name' => 'UK Northern Depot', 'Max_Capacity' => 40000, 'operational_status' => 'active'],
            ['address_ID' => 8, 'Name' => 'Australia Main', 'Max_Capacity' => 60000, 'operational_status' => 'active'],
            ['address_ID' => 9, 'Name' => 'Melbourne Annex', 'Max_Capacity' => 30000, 'operational_status' => 'maintenance'],
            ['address_ID' => 10, 'Name' => 'German Central', 'Max_Capacity' => 110000, 'operational_status' => 'active'],
        ]);

        DB::table('warehouse_contacts')->insert([
            ['warehouse_ID' => 1, 'contact_ID' => 1], ['warehouse_ID' => 1, 'contact_ID' => 2],
            ['warehouse_ID' => 2, 'contact_ID' => 3], ['warehouse_ID' => 2, 'contact_ID' => 4],
            ['warehouse_ID' => 3, 'contact_ID' => 5],
            ['warehouse_ID' => 4, 'contact_ID' => 1], ['warehouse_ID' => 4, 'contact_ID' => 4],
            ['warehouse_ID' => 5, 'contact_ID' => 2], ['warehouse_ID' => 5, 'contact_ID' => 3],
            ['warehouse_ID' => 6, 'contact_ID' => 6],
        ]);

        DB::table('warehouse_sections')->insert([
            ['s_Capacity' => 20000, 'section_name' => 'General Storage A', 'temperature_zone' => 'ambient'],
            ['s_Capacity' => 15000, 'section_name' => 'Cold Room 1', 'temperature_zone' => 'chilled'],
            ['s_Capacity' => 10000, 'section_name' => 'Frozen Goods B', 'temperature_zone' => 'frozen'],
            ['s_Capacity' => 5000, 'section_name' => 'Hazardous Materials C', 'temperature_zone' => 'controlled'],
            ['s_Capacity' => 18000, 'section_name' => 'Bulk Storage D', 'temperature_zone' => 'ambient'],
            ['s_Capacity' => 12000, 'section_name' => 'Receiving Area E', 'temperature_zone' => 'ambient'],
            ['s_Capacity' => 8000, 'section_name' => 'Shipping Prep F', 'temperature_zone' => 'ambient'],
            ['s_Capacity' => 7000, 'section_name' => 'Returns Processing G', 'temperature_zone' => 'ambient'],
            ['s_Capacity' => 9000, 'section_name' => 'High Value Products H', 'temperature_zone' => 'controlled'],
            ['s_Capacity' => 11000, 'section_name' => 'Oversized Storage I', 'temperature_zone' => 'ambient'],
        ]);

        DB::table('includes')->insert([
            ['warehouse_ID' => 1, 'section_ID' => 1], ['warehouse_ID' => 1, 'section_ID' => 2], ['warehouse_ID' => 1, 'section_ID' => 3],
            ['warehouse_ID' => 2, 'section_ID' => 1], ['warehouse_ID' => 2, 'section_ID' => 4],
            ['warehouse_ID' => 3, 'section_ID' => 5], ['warehouse_ID' => 3, 'section_ID' => 6],
            ['warehouse_ID' => 4, 'section_ID' => 7],
            ['warehouse_ID' => 5, 'section_ID' => 8],
            ['warehouse_ID' => 6, 'section_ID' => 9],
        ]);

        DB::table('subsections')->insert([
            ['sub_Hype' => 'Pallet Racking A1', 'Sub_Capacity' => 5000, 'subsection_name' => 'Rack A-01'],
            ['sub_Hype' => 'Shelving Unit B1', 'Sub_Capacity' => 2000, 'subsection_name' => 'Shelf B-01'],
            ['sub_Hype' => 'Freezer Zone C1', 'Sub_Capacity' => 3000, 'subsection_name' => 'Freezer C-01'],
            ['sub_Hype' => 'Chemical Locker D1', 'Sub_Capacity' => 1000, 'subsection_name' => 'Locker D-01'],
            ['sub_Hype' => 'Bulk Floor Stack E1', 'Sub_Capacity' => 7000, 'subsection_name' => 'Floor E-01'],
            ['sub_Hype' => 'Quarantine Area F1', 'Sub_Capacity' => 1500, 'subsection_name' => 'Quarantine F-01'],
            ['sub_Hype' => 'Picking Station G1', 'Sub_Capacity' => 2500, 'subsection_name' => 'Picking G-01'],
            ['sub_Hype' => 'Loading Dock H1', 'Sub_Capacity' => 4000, 'subsection_name' => 'Dock H-01'],
            ['sub_Hype' => 'Security Cage I1', 'Sub_Capacity' => 800, 'subsection_name' => 'Cage I-01'],
            ['sub_Hype' => 'Overhead Storage J1', 'Sub_Capacity' => 1000, 'subsection_name' => 'Overhead J-01'],
        ]);

        DB::table('divides')->insert([
            ['Sub_Section_ID' => 1, 'section_ID' => 1], ['Sub_Section_ID' => 2, 'section_ID' => 1],
            ['Sub_Section_ID' => 3, 'section_ID' => 2],
            ['Sub_Section_ID' => 4, 'section_ID' => 4],
            ['Sub_Section_ID' => 5, 'section_ID' => 5],
            ['Sub_Section_ID' => 6, 'section_ID' => 1],
            ['Sub_Section_ID' => 7, 'section_ID' => 6],
            ['Sub_Section_ID' => 8, 'section_ID' => 7],
            ['Sub_Section_ID' => 9, 'section_ID' => 9],
            ['Sub_Section_ID' => 10, 'section_ID' => 1],
        ]);

        DB::table('cold_storages')->insert([
            ['Sub_section_ID' => 3, 'Freezer_Sec_Assigned' => 'Freezer A', 'min_temperature' => -25.00, 'max_temperature' => -18.00],
            ['Sub_section_ID' => 1, 'Freezer_Sec_Assigned' => 'Chilled B', 'min_temperature' => 0.00, 'max_temperature' => 5.00],
            ['Sub_section_ID' => 2, 'Freezer_Sec_Assigned' => 'Cooler C', 'min_temperature' => 6.00, 'max_temperature' => 10.00],
        ]);

        DB::table('bulk_storages')->insert([
            ['Sub_section_ID' => 5, 'Weight_Measurement' => 'Tons', 'max_weight' => 10000.00],
            ['Sub_section_ID' => 8, 'Weight_Measurement' => 'Kilograms', 'max_weight' => 5000.00],
            ['Sub_section_ID' => 10, 'Weight_Measurement' => 'Pounds', 'max_weight' => 20000.00],
        ]);

        DB::table('hazardous_materials')->insert([
            ['Sub_section_ID' => 4, 'Hazardous_Class' => 'Flammable Liquids', 'safety_level' => 'Level 3'],
            ['Sub_section_ID' => 9, 'Hazardous_Class' => 'Corrosive Substances', 'safety_level' => 'Level 2'],
        ]);

        DB::table('products')->insert([
            ['Product_Name' => 'Laptop Pro X', 'Product_Date' => '2023-01-15', 'description' => 'High-performance laptop', 'barcode' => 'LPX987654321', 'weight' => 2.50, 'dimensions' => '35x24x2', 'status' => 'active', 'image' => 'https://placehold.co/100x100/A0A0A0/FFFFFF?text=Laptop', 'price' => 1200.00],
            ['Product_Name' => 'Wireless Mouse M3', 'Product_Date' => '2023-02-01', 'description' => 'Ergonomic wireless mouse', 'barcode' => 'WMM3-001', 'weight' => 0.10, 'dimensions' => '10x6x3', 'status' => 'active', 'image' => 'https://placehold.co/100x100/B0B0B0/FFFFFF?text=Mouse', 'price' => 25.00],
            ['Product_Name' => 'Organic Coffee Beans', 'Product_Date' => '2023-03-10', 'description' => 'Fair trade arabica beans', 'barcode' => 'OCB-FAIR23', 'weight' => 1.00, 'dimensions' => '20x10x8', 'status' => 'active', 'image' => 'https://placehold.co/100x100/C0C0C0/FFFFFF?text=Coffee', 'price' => 15.50],
            ['Product_Name' => 'Frozen Pizza Margherita', 'Product_Date' => '2023-04-05', 'description' => 'Ready-to-bake pizza', 'barcode' => 'FPM-CHEESY', 'weight' => 0.80, 'dimensions' => '25x25x3', 'status' => 'active', 'image' => 'https://placehold.co/100x100/D0D0D0/FFFFFF?text=Pizza', 'price' => 7.99],
            ['Product_Name' => 'Industrial Cleaning Fluid', 'Product_Date' => '2023-05-20', 'description' => 'Concentrated industrial cleaner', 'barcode' => 'ICF-HAZARD', 'weight' => 5.00, 'dimensions' => '30x15x15', 'status' => 'active', 'image' => 'https://placehold.co/100x100/E0E0E0/FFFFFF?text=Cleaner', 'price' => 50.00],
            ['Product_Name' => 'Winter Jacket Alpine', 'Product_Date' => '2023-06-01', 'description' => 'Waterproof alpine jacket', 'barcode' => 'WJA-2023', 'weight' => 1.20, 'dimensions' => '60x40x10', 'status' => 'active', 'image' => 'https://placehold.co/100x100/F0F0F0/FFFFFF?text=Jacket', 'price' => 150.00],
            ['Product_Name' => 'Smartphone Model S', 'Product_Date' => '2023-07-11', 'description' => 'Latest smartphone with AI camera', 'barcode' => 'SMS-ULTRA', 'weight' => 0.20, 'dimensions' => '16x8x1', 'status' => 'active', 'image' => 'https://placehold.co/100x100/A1A1A1/FFFFFF?text=Phone', 'price' => 999.00],
            ['Product_Name' => 'Protein Powder Vanilla', 'Product_Date' => '2023-08-01', 'description' => 'Whey protein isolate, 2kg', 'barcode' => 'PPV-2KG', 'weight' => 2.00, 'dimensions' => '25x15x15', 'status' => 'active', 'image' => 'https://placehold.co/100x100/B1B1B1/FFFFFF?text=Protein', 'price' => 35.00],
            ['Product_Name' => 'Luxury Watch Chrono', 'Product_Date' => '2023-09-01', 'description' => 'Swiss made automatic watch', 'barcode' => 'LWC-SWISS', 'weight' => 0.30, 'dimensions' => '10x10x8', 'status' => 'active', 'image' => 'https://placehold.co/100x100/C1C1C1/FFFFFF?text=Watch', 'price' => 2500.00],
            ['Product_Name' => 'Garden Hose 50ft', 'Product_Date' => '2023-10-01', 'description' => 'Reinforced garden hose', 'barcode' => 'GH-50FT', 'weight' => 3.00, 'dimensions' => '40x40x10', 'status' => 'active', 'image' => 'https://placehold.co/100x100/D1D1D1/FFFFFF?text=Hose', 'price' => 29.99],
        ]);

        DB::table('product_types')->insert([
            ['Type_Name' => 'Electronics', 'Section_ID' => 1, 'storage_requirements' => 'Dry, cool place, anti-static'],
            ['Type_Name' => 'Perishables', 'Section_ID' => 2, 'storage_requirements' => 'Refrigerated, temperature controlled'],
            ['Type_Name' => 'Frozen Foods', 'Section_ID' => 3, 'storage_requirements' => 'Deep frozen, constant temperature -18C'],
            ['Type_Name' => 'Chemicals', 'Section_ID' => 4, 'storage_requirements' => 'Ventilated, isolated, safety protocols'],
            ['Type_Name' => 'Consumer Goods', 'Section_ID' => 1, 'storage_requirements' => 'Standard warehouse conditions'],
            ['Type_Name' => 'Apparel', 'Section_ID' => 1, 'storage_requirements' => 'Dry, clean, hanger space'],
            ['Type_Name' => 'Perishables', 'Section_ID' => 2, 'storage_requirements' => 'Chilled, humidity controlled'],
            ['Type_Name' => 'Building Materials', 'Section_ID' => 5, 'storage_requirements' => 'Outdoor/Indoor bulk, weather protected'],
            ['Type_Name' => 'High Value Items', 'Section_ID' => 9, 'storage_requirements' => 'Secured cage, climate controlled'],
            ['Type_Name' => 'Tools & Hardware', 'Section_ID' => 1, 'storage_requirements' => 'Dry, organized shelving'],
        ]);

        DB::table('belongs_tos')->insert([
            ['Product_ID' => 1, 'Type_ID' => 1], ['Product_ID' => 2, 'Type_ID' => 1],
            ['Product_ID' => 3, 'Type_ID' => 2], ['Product_ID' => 7, 'Type_ID' => 1],
            ['Product_ID' => 4, 'Type_ID' => 3], ['Product_ID' => 8, 'Type_ID' => 5],
            ['Product_ID' => 5, 'Type_ID' => 4], ['Product_ID' => 6, 'Type_ID' => 6],
            ['Product_ID' => 9, 'Type_ID' => 9], ['Product_ID' => 10, 'Type_ID' => 10],
        ]);

        DB::table('stocks')->insert([
            ['Quantity' => 100, 'Manufactured_Date' => '2023-01-01', 'Use_By_Date' => '2024-12-31', 'batch_number' => 'BATCH-LPX-001', 'Product_ID' => 1, 'Sub_section_ID' => 1, 'last_checked' => now()],
            ['Quantity' => 250, 'Manufactured_Date' => '2023-01-20', 'Use_By_Date' => '2025-01-20', 'batch_number' => 'BATCH-WMM-002', 'Product_ID' => 2, 'Sub_section_ID' => 2, 'last_checked' => now()],
            ['Quantity' => 50, 'Manufactured_Date' => '2023-03-01', 'Use_By_Date' => '2024-03-01', 'batch_number' => 'BATCH-OCB-003', 'Product_ID' => 3, 'Sub_section_ID' => 1, 'last_checked' => now()],
            ['Quantity' => 75, 'Manufactured_Date' => '2023-03-25', 'Use_By_Date' => '2024-09-30', 'batch_number' => 'BATCH-FPM-004', 'Product_ID' => 4, 'Sub_section_ID' => 3, 'last_checked' => now()],
            ['Quantity' => 30, 'Manufactured_Date' => '2023-05-10', 'Use_By_Date' => '2025-05-10', 'batch_number' => 'BATCH-ICF-005', 'Product_ID' => 5, 'Sub_section_ID' => 4, 'last_checked' => now()],
            ['Quantity' => 120, 'Manufactured_Date' => '2023-05-25', 'Use_By_Date' => '2026-12-31', 'batch_number' => 'BATCH-WJA-006', 'Product_ID' => 6, 'Sub_section_ID' => 2, 'last_checked' => now()],
            ['Quantity' => 90, 'Manufactured_Date' => '2023-07-01', 'Use_By_Date' => '2025-07-01', 'batch_number' => 'BATCH-SMS-007', 'Product_ID' => 7, 'Sub_section_ID' => 1, 'last_checked' => now()],
            ['Quantity' => 60, 'Manufactured_Date' => '2023-07-20', 'Use_By_Date' => '2024-07-20', 'batch_number' => 'BATCH-PPV-008', 'Product_ID' => 8, 'Sub_section_ID' => 5, 'last_checked' => now()],
            ['Quantity' => 20, 'Manufactured_Date' => '2023-08-20', 'Use_By_Date' => '2030-08-20', 'batch_number' => 'BATCH-LWC-009', 'Product_ID' => 9, 'Sub_section_ID' => 9, 'last_checked' => now()],
            ['Quantity' => 80, 'Manufactured_Date' => '2023-09-15', 'Use_By_Date' => '2027-09-15', 'batch_number' => 'BATCH-GH-010', 'Product_ID' => 10, 'Sub_section_ID' => 10, 'last_checked' => now()],
        ]);

        DB::table('orders')->insert([
            ['shipping_address_ID' => 1, 'Shipping_Date' => '2024-06-15', 'Status' => 'pending', 'priority' => 'high', 'notes' => 'Customer requests morning delivery', 'total_amount' => 12000.00, 'processed_at' => null, 'shipped_at' => null, 'delivered_at' => null],
            ['shipping_address_ID' => 2, 'Shipping_Date' => '2024-06-16', 'Status' => 'processing', 'priority' => 'medium', 'notes' => 'Fragile items, handle with care', 'total_amount' => 1550.00, 'processed_at' => now(), 'shipped_at' => null, 'delivered_at' => null],
            ['shipping_address_ID' => 3, 'Shipping_Date' => '2024-06-17', 'Status' => 'shipped', 'priority' => 'low', 'notes' => 'Standard delivery', 'total_amount' => 1000.00, 'processed_at' => now(), 'shipped_at' => now(), 'delivered_at' => null],
            ['shipping_address_ID' => 4, 'Shipping_Date' => '2024-06-18', 'Status' => 'delivered', 'priority' => 'urgent', 'notes' => 'Express delivery, signed receipt required', 'total_amount' => 2250.00, 'processed_at' => now(), 'shipped_at' => now(), 'delivered_at' => now()],
            ['shipping_address_ID' => 5, 'Shipping_Date' => '2024-06-19', 'Status' => 'cancelled', 'priority' => 'medium', 'notes' => 'Customer cancelled order', 'total_amount' => 0.00, 'processed_at' => null, 'shipped_at' => null, 'delivered_at' => null],
            ['shipping_address_ID' => 6, 'Shipping_Date' => '2024-06-20', 'Status' => 'pending', 'priority' => 'high', 'notes' => 'International shipment', 'total_amount' => 3750.00, 'processed_at' => null, 'shipped_at' => null, 'delivered_at' => null],
            ['shipping_address_ID' => 7, 'Shipping_Date' => '2024-06-21', 'Status' => 'processing', 'priority' => 'medium', 'notes' => 'Large volume order', 'total_amount' => 2800.00, 'processed_at' => now(), 'shipped_at' => null, 'delivered_at' => null],
            ['shipping_address_ID' => 8, 'Shipping_Date' => '2024-06-22', 'Status' => 'shipped', 'priority' => 'low', 'notes' => 'Rural delivery', 'total_amount' => 799.00, 'processed_at' => now(), 'shipped_at' => now(), 'delivered_at' => null],
            ['shipping_address_ID' => 9, 'Shipping_Date' => '2024-06-23', 'Status' => 'delivered', 'priority' => 'urgent', 'notes' => 'Next-day delivery', 'total_amount' => 2500.00, 'processed_at' => now(), 'shipped_at' => now(), 'delivered_at' => now()],
            ['shipping_address_ID' => 10, 'Shipping_Date' => '2024-06-24', 'Status' => 'pending', 'priority' => 'medium', 'notes' => 'Requires special handling', 'total_amount' => 500.00, 'processed_at' => null, 'shipped_at' => null, 'delivered_at' => null],
        ]);

        DB::table('order_contacts')->insert([
            ['Order_ID' => 1, 'contact_ID' => 1], ['Order_ID' => 1, 'contact_ID' => 2],
            ['Order_ID' => 2, 'contact_ID' => 3], ['Order_ID' => 2, 'contact_ID' => 4],
            ['Order_ID' => 3, 'contact_ID' => 5],
            ['Order_ID' => 4, 'contact_ID' => 7], ['Order_ID' => 4, 'contact_ID' => 8],
            ['Order_ID' => 5, 'contact_ID' => 9],
            ['Order_ID' => 6, 'contact_ID' => 1], ['Order_ID' => 6, 'contact_ID' => 10],
        ]);

        DB::table('distributors')->insert([
            ['Distributor_Name' => 'Global Logistics Inc.', 'address_ID' => 1, 'Distributing_Location' => 'North America', 'tax_id' => 'TAXGLI123', 'account_manager' => 'John Doe'],
            ['Distributor_Name' => 'Euro Freight Solutions', 'address_ID' => 6, 'Distributing_Location' => 'Europe', 'tax_id' => 'TAXEFS456', 'account_manager' => 'Jane Smith'],
            ['Distributor_Name' => 'Asia Pacific Distribution', 'address_ID' => 8, 'Distributing_Location' => 'Asia-Pacific', 'tax_id' => 'TAXAPD789', 'account_manager' => 'David Lee'],
            ['Distributor_Name' => 'Local Deliveries Ltd.', 'address_ID' => 2, 'Distributing_Location' => 'Northeast USA', 'tax_id' => 'TAXLDL101', 'account_manager' => 'Sarah Chen'],
            ['Distributor_Name' => 'West Coast Express', 'address_ID' => 5, 'Distributing_Location' => 'Western USA', 'tax_id' => 'TAXWCE202', 'account_manager' => 'Mike Johnson'],
            ['Distributor_Name' => 'Canadian Cargo Corp.', 'address_ID' => 4, 'Distributing_Location' => 'Canada', 'tax_id' => 'TAXCCC303', 'account_manager' => 'Emily White'],
            ['Distributor_Name' => 'UK Distribution Network', 'address_ID' => 7, 'Distributing_Location' => 'United Kingdom', 'tax_id' => 'TAXUKDN404', 'account_manager' => 'Chris Green'],
            ['Distributor_Name' => 'Aus Wide Freight', 'address_ID' => 9, 'Distributing_Location' => 'Australia', 'tax_id' => 'TAXAWF505', 'account_manager' => 'Olivia Black'],
            ['Distributor_Name' => 'Germany Shipping Services', 'address_ID' => 10, 'Distributing_Location' => 'Germany', 'tax_id' => 'TAXGSS606', 'account_manager' => 'Max Weber'],
            ['Distributor_Name' => 'South American Cargo', 'address_ID' => 3, 'Distributing_Location' => 'South America', 'tax_id' => 'TAXSAC707', 'account_manager' => 'Maria Garcia'],
        ]);

        DB::table('distributor_contacts')->insert([
            ['Distributor_ID' => 1, 'contact_ID' => 1], ['Distributor_ID' => 1, 'contact_ID' => 2],
            ['Distributor_ID' => 2, 'contact_ID' => 6], ['Distributor_ID' => 2, 'contact_ID' => 5],
            ['Distributor_ID' => 3, 'contact_ID' => 9], ['Distributor_ID' => 3, 'contact_ID' => 7],
            ['Distributor_ID' => 4, 'contact_ID' => 3],
            ['Distributor_ID' => 5, 'contact_ID' => 4],
            ['Distributor_ID' => 6, 'contact_ID' => 10],
            ['Distributor_ID' => 7, 'contact_ID' => 1],
        ]);

        DB::table('suppliers')->insert([
            ['address_ID' => 1, 'Supplier_Name' => 'Tech Components Co.', 'reliability_rating' => 0.95, 'lead_time_days' => 7],
            ['address_ID' => 2, 'Supplier_Name' => 'Organic Food Growers', 'reliability_rating' => 0.98, 'lead_time_days' => 3],
            ['address_ID' => 3, 'Supplier_Name' => 'Chemicals R Us', 'reliability_rating' => 0.90, 'lead_time_days' => 14],
            ['address_ID' => 4, 'Supplier_Name' => 'Fashion Textile Mills', 'reliability_rating' => 0.92, 'lead_time_days' => 10],
            ['address_ID' => 5, 'Supplier_Name' => 'Smart Device Mfg.', 'reliability_rating' => 0.97, 'lead_time_days' => 5],
            ['address_ID' => 6, 'Supplier_Name' => 'Nutritional Supplements Inc.', 'reliability_rating' => 0.96, 'lead_time_days' => 6],
            ['address_ID' => 7, 'Supplier_Name' => 'Luxury Goods Artisans', 'reliability_rating' => 0.99, 'lead_time_days' => 21],
            ['address_ID' => 8, 'Supplier_Name' => 'Industrial Supplies Ltd.', 'reliability_rating' => 0.93, 'lead_time_days' => 8],
            ['address_ID' => 9, 'Supplier_Name' => 'Gardening Essentials', 'reliability_rating' => 0.94, 'lead_time_days' => 7],
            ['address_ID' => 10, 'Supplier_Name' => 'Office Supplies Corp.', 'reliability_rating' => 0.91, 'lead_time_days' => 4],
        ]);

        DB::table('supplier_contacts')->insert([
            ['Supplier_ID' => 1, 'contact_ID' => 1], ['Supplier_ID' => 1, 'contact_ID' => 2],
            ['Supplier_ID' => 2, 'contact_ID' => 3], ['Supplier_ID' => 2, 'contact_ID' => 4],
            ['Supplier_ID' => 3, 'contact_ID' => 5],
            ['Supplier_ID' => 4, 'contact_ID' => 6],
            ['Supplier_ID' => 5, 'contact_ID' => 7], ['Supplier_ID' => 5, 'contact_ID' => 8],
            ['Supplier_ID' => 6, 'contact_ID' => 9],
            ['Supplier_ID' => 7, 'contact_ID' => 10],
        ]);

        DB::table('incoming_orders')->insert([
            ['Supplier_ID' => 1, 'Order_ID' => 1, 'Product_ID' => 1, 'expected_delivery' => '2024-06-14', 'quantity_ordered' => 50, 'quantity_received' => 50, 'unit_price' => 1200.00, 'total_price' => 60000.00],
            ['Supplier_ID' => 2, 'Order_ID' => 2, 'Product_ID' => 3, 'expected_delivery' => '2024-06-15', 'quantity_ordered' => 100, 'quantity_received' => 100, 'unit_price' => 15.50, 'total_price' => 1550.00],
            ['Supplier_ID' => 3, 'Order_ID' => 3, 'Product_ID' => 5, 'expected_delivery' => '2024-06-16', 'quantity_ordered' => 20, 'quantity_received' => 20, 'unit_price' => 50.00, 'total_price' => 1000.00],
            ['Supplier_ID' => 4, 'Order_ID' => 4, 'Product_ID' => 6, 'expected_delivery' => '2024-06-17', 'quantity_ordered' => 70, 'quantity_received' => 70, 'unit_price' => 150.00, 'total_price' => 10500.00],
            ['Supplier_ID' => 5, 'Order_ID' => 5, 'Product_ID' => 7, 'expected_delivery' => '2024-06-18', 'quantity_ordered' => 40, 'quantity_received' => NULL, 'unit_price' => 999.00, 'total_price' => 39960.00],
            ['Supplier_ID' => 1, 'Order_ID' => 6, 'Product_ID' => 2, 'expected_delivery' => '2024-06-19', 'quantity_ordered' => 150, 'quantity_received' => NULL, 'unit_price' => 25.00, 'total_price' => 3750.00],
            ['Supplier_ID' => 2, 'Order_ID' => 7, 'Product_ID' => 8, 'expected_delivery' => '2024-06-20', 'quantity_ordered' => 80, 'quantity_received' => NULL, 'unit_price' => 35.00, 'total_price' => 2800.00],
            ['Supplier_ID' => 3, 'Order_ID' => 8, 'Product_ID' => 4, 'expected_delivery' => '2024-06-21', 'quantity_ordered' => 25, 'quantity_received' => NULL, 'unit_price' => 7.99, 'total_price' => 199.75],
            ['Supplier_ID' => 4, 'Order_ID' => 9, 'Product_ID' => 9, 'expected_delivery' => '2024-06-22', 'quantity_ordered' => 15, 'quantity_received' => NULL, 'unit_price' => 2500.00, 'total_price' => 37500.00],
            ['Supplier_ID' => 5, 'Order_ID' => 10, 'Product_ID' => 10, 'expected_delivery' => '2024-06-23', 'quantity_ordered' => 90, 'quantity_received' => NULL, 'unit_price' => 29.99, 'total_price' => 2699.10],
        ]);

        DB::table('outgoing_orders')->insert([
            ['Order_ID' => 1, 'Product_ID' => 1, 'Distribution_ID' => 1, 'quantity_shipped' => 10, 'tracking_number' => 'TRK001LPX', 'carrier' => 'FedEx', 'unit_price' => 1200.00, 'total_price' => 12000.00],
            ['Order_ID' => 2, 'Product_ID' => 3, 'Distribution_ID' => 4, 'quantity_shipped' => 20, 'tracking_number' => 'TRK002OCB', 'carrier' => 'UPS', 'unit_price' => 15.50, 'total_price' => 310.00],
            ['Order_ID' => 3, 'Product_ID' => 5, 'Distribution_ID' => 2, 'quantity_shipped' => 5, 'tracking_number' => 'TRK003ICF', 'carrier' => 'DHL', 'unit_price' => 50.00, 'total_price' => 250.00],
            ['Order_ID' => 4, 'Product_ID' => 6, 'Distribution_ID' => 5, 'quantity_shipped' => 15, 'tracking_number' => 'TRK004WJA', 'carrier' => 'USPS', 'unit_price' => 150.00, 'total_price' => 2250.00],
            ['Order_ID' => 5, 'Product_ID' => 7, 'Distribution_ID' => 1, 'quantity_shipped' => 5, 'tracking_number' => 'TRK005SMS', 'carrier' => 'FedEx', 'unit_price' => 999.00, 'total_price' => 4995.00],
            ['Order_ID' => 6, 'Product_ID' => 2, 'Distribution_ID' => 6, 'quantity_shipped' => 30, 'tracking_number' => 'TRK006WMM', 'carrier' => 'Canada Post', 'unit_price' => 25.00, 'total_price' => 750.00],
            ['Order_ID' => 7, 'Product_ID' => 8, 'Distribution_ID' => 7, 'quantity_shipped' => 10, 'tracking_number' => 'TRK007PPV', 'carrier' => 'Royal Mail', 'unit_price' => 35.00, 'total_price' => 350.00],
            ['Order_ID' => 8, 'Product_ID' => 4, 'Distribution_ID' => 8, 'quantity_shipped' => 10, 'tracking_number' => 'TRK008FPM', 'carrier' => 'Australia Post', 'unit_price' => 7.99, 'total_price' => 79.90],
            ['Order_ID' => 9, 'Product_ID' => 9, 'Distribution_ID' => 9, 'quantity_shipped' => 2, 'tracking_number' => 'TRK009LWC', 'carrier' => 'Deutsche Post', 'unit_price' => 2500.00, 'total_price' => 5000.00],
            ['Order_ID' => 10, 'Product_ID' => 10, 'Distribution_ID' => 10, 'quantity_shipped' => 20, 'tracking_number' => 'TRK010GH', 'carrier' => 'Local Courier', 'unit_price' => 29.99, 'total_price' => 599.80],
        ]);

        DB::table('order_trackings')->insert([
            ['Tracking_Comments' => 'Order created and pending processing.', 'Supplier_ID' => NULL, 'Distributor_ID' => NULL, 'Order_ID' => 1, 'Timestamp' => now(), 'status_change' => 'created'],
            ['Tracking_Comments' => 'Order picked and packed.', 'Supplier_ID' => NULL, 'Distributor_ID' => NULL, 'Order_ID' => 2, 'Timestamp' => now(), 'status_change' => 'processed'],
            ['Tracking_Comments' => 'Shipped from warehouse.', 'Supplier_ID' => NULL, 'Distributor_ID' => 2, 'Order_ID' => 3, 'Timestamp' => now(), 'status_change' => 'shipped'],
            ['Tracking_Comments' => 'Delivered to customer.', 'Supplier_ID' => NULL, 'Distributor_ID' => 5, 'Order_ID' => 4, 'Timestamp' => now(), 'status_change' => 'delivered'],
            ['Tracking_Comments' => 'Order cancelled by customer request.', 'Supplier_ID' => NULL, 'Distributor_ID' => NULL, 'Order_ID' => 5, 'Timestamp' => now(), 'status_change' => 'cancelled'],
            ['Tracking_Comments' => 'New incoming order from Tech Components Co.', 'Supplier_ID' => 1, 'Distributor_ID' => NULL, 'Order_ID' => 6, 'Timestamp' => now(), 'status_change' => 'created'],
            ['Tracking_Comments' => 'Large order started processing.', 'Supplier_ID' => NULL, 'Distributor_ID' => NULL, 'Order_ID' => 7, 'Timestamp' => now(), 'status_change' => 'processed'],
            ['Tracking_Comments' => 'Rural shipment dispatched.', 'Supplier_ID' => NULL, 'Distributor_ID' => 8, 'Order_ID' => 8, 'Timestamp' => now(), 'status_change' => 'shipped'],
            ['Tracking_Comments' => 'Next-day delivery completed.', 'Supplier_ID' => NULL, 'Distributor_ID' => 9, 'Order_ID' => 9, 'Timestamp' => now(), 'status_change' => 'delivered'],
            ['Tracking_Comments' => 'Special handling required, order reassigned.', 'Supplier_ID' => NULL, 'Distributor_ID' => NULL, 'Order_ID' => 10, 'Timestamp' => now(), 'status_change' => 'created'],
        ]);

        DB::table('employees')->insert([
            ['F_Name' => 'Alice', 'L_Name' => 'Smith', 'DoB' => '1985-03-10', 'address_ID' => 1, 'hire_date' => '2010-01-15', 'position' => 'Warehouse Manager', 'department' => 'Management', 'salary' => 75000.00],
            ['F_Name' => 'Bob', 'L_Name' => 'Johnson', 'DoB' => '1990-07-22', 'address_ID' => 2, 'hire_date' => '2015-05-20', 'position' => 'Order Processor', 'department' => 'Operations', 'salary' => 45000.00],
            ['F_Name' => 'Charlie', 'L_Name' => 'Brown', 'DoB' => '1988-11-05', 'address_ID' => 3, 'hire_date' => '2012-09-01', 'position' => 'Inventory Clerk', 'department' => 'Operations', 'salary' => 40000.00],
            ['F_Name' => 'Diana', 'L_Name' => 'Prince', 'DoB' => '1992-04-12', 'address_ID' => 4, 'hire_date' => '2018-03-10', 'position' => 'Shipping Clerk', 'department' => 'Logistics', 'salary' => 42000.00],
            ['F_Name' => 'Eve', 'L_Name' => 'Davis', 'DoB' => '1980-01-30', 'address_ID' => 5, 'hire_date' => '2008-07-01', 'position' => 'Supervisor', 'department' => 'Management', 'salary' => 60000.00],
            ['F_Name' => 'Frank', 'L_Name' => 'White', 'DoB' => '1995-02-28', 'address_ID' => 6, 'hire_date' => '2020-11-11', 'position' => 'Order Processor', 'department' => 'Operations', 'salary' => 46000.00],
            ['F_Name' => 'Grace', 'L_Name' => 'Lee', 'DoB' => '1993-09-19', 'address_ID' => 7, 'hire_date' => '2019-06-01', 'position' => 'Inventory Clerk', 'department' => 'Operations', 'salary' => 41000.00],
            ['F_Name' => 'Henry', 'L_Name' => 'Clark', 'DoB' => '1987-06-06', 'address_ID' => 8, 'hire_date' => '2014-02-14', 'position' => 'Shipping Clerk', 'department' => 'Logistics', 'salary' => 43000.00],
            ['F_Name' => 'Ivy', 'L_Name' => 'Baker', 'DoB' => '1998-12-01', 'address_ID' => 9, 'hire_date' => '2022-01-01', 'position' => 'Order Processor', 'department' => 'Operations', 'salary' => 44000.00],
            ['F_Name' => 'Jack', 'L_Name' => 'Green', 'DoB' => '1983-05-15', 'address_ID' => 10, 'hire_date' => '2011-04-01', 'position' => 'Supervisor', 'department' => 'Management', 'salary' => 62000.00],
        ]);

        DB::table('employee_contacts')->insert([
            ['Emp_ID' => 1, 'contact_ID' => 1], ['Emp_ID' => 1, 'contact_ID' => 2],
            ['Emp_ID' => 2, 'contact_ID' => 3], ['Emp_ID' => 2, 'contact_ID' => 4],
            ['Emp_ID' => 3, 'contact_ID' => 5],
            ['Emp_ID' => 4, 'contact_ID' => 6],
            ['Emp_ID' => 5, 'contact_ID' => 7], ['Emp_ID' => 5, 'contact_ID' => 8],
            ['Emp_ID' => 6, 'contact_ID' => 9],
            ['Emp_ID' => 7, 'contact_ID' => 10],
        ]);

        DB::table('supervisors')->insert([
            ['Emp_ID' => 5, 'team_size' => 10, 'department' => 'Operations'],
            ['Emp_ID' => 10, 'team_size' => 8, 'department' => 'Logistics'],
        ]);

        DB::table('order_processors')->insert([
            ['Emp_ID' => 2, 'Avg_time_Processing' => 30.50, 'Accuracy_Rate' => 0.98, 'orders_processed' => 1500],
            ['Emp_ID' => 6, 'Avg_time_Processing' => 28.00, 'Accuracy_Rate' => 0.99, 'orders_processed' => 1200],
            ['Emp_ID' => 9, 'Avg_time_Processing' => 32.00, 'Accuracy_Rate' => 0.97, 'orders_processed' => 800],
            ['Emp_ID' => 5, 'Avg_time_Processing' => 29.00, 'Accuracy_Rate' => 0.99, 'orders_processed' => 100],
        ]);

        DB::table('inventory_clerks')->insert([
            ['Emp_ID' => 3, 'Forklift_Certification' => TRUE, 'Items_Processed_PerDay' => 500, 'inventory_accuracy' => 0.99],
            ['Emp_ID' => 7, 'Forklift_Certification' => FALSE, 'Items_Processed_PerDay' => 450, 'inventory_accuracy' => 0.98],
        ]);

        DB::table('shipping_clerks')->insert([
            ['Emp_ID' => 4, 'Error_Rate' => 0.01, 'Packages_PerDay' => 100, 'shipping_accuracy' => 0.99],
            ['Emp_ID' => 8, 'Error_Rate' => 0.02, 'Packages_PerDay' => 90, 'shipping_accuracy' => 0.98],
        ]);

        DB::table('supervisions')->insert([
            ['Emp_ID' => 2, 'Supervisor_ID' => 5, 'relationship_type' => 'direct', 'start_date' => '2015-05-20', 'end_date' => NULL],
            ['Emp_ID' => 3, 'Supervisor_ID' => 5, 'relationship_type' => 'direct', 'start_date' => '2012-09-01', 'end_date' => NULL],
            ['Emp_ID' => 4, 'Supervisor_ID' => 10, 'relationship_type' => 'direct', 'start_date' => '2018-03-10', 'end_date' => NULL],
            ['Emp_ID' => 6, 'Supervisor_ID' => 5, 'relationship_type' => 'direct', 'start_date' => '2020-11-11', 'end_date' => NULL],
            ['Emp_ID' => 7, 'Supervisor_ID' => 5, 'relationship_type' => 'direct', 'start_date' => '2019-06-01', 'end_date' => NULL],
            ['Emp_ID' => 8, 'Supervisor_ID' => 10, 'relationship_type' => 'direct', 'start_date' => '2014-02-14', 'end_date' => NULL],
            ['Emp_ID' => 9, 'Supervisor_ID' => 5, 'relationship_type' => 'direct', 'start_date' => '2022-01-01', 'end_date' => NULL],
            ['Emp_ID' => 1, 'Supervisor_ID' => 10, 'relationship_type' => 'departmental', 'start_date' => '2020-01-01', 'end_date' => NULL],
            ['Emp_ID' => 5, 'Supervisor_ID' => 10, 'relationship_type' => 'departmental', 'start_date' => '2008-07-01', 'end_date' => NULL],
            ['Emp_ID' => 10, 'Supervisor_ID' => 5, 'relationship_type' => 'departmental', 'start_date' => '2011-04-01', 'end_date' => NULL],
        ]);

        DB::table('order_processings')->insert([
            ['Emp_ID' => 2, 'Order_ID' => 1, 'Processing_Start' => '2024-06-14 10:15:00', 'Processing_End' => '2024-06-14 10:45:00', 'Status' => 'completed', 'notes' => 'Standard pick and pack.'],
            ['Emp_ID' => 2, 'Order_ID' => 2, 'Processing_Start' => '2024-06-15 12:00:00', 'Processing_End' => '2024-06-15 12:45:00', 'Status' => 'completed', 'notes' => 'Fragile items handled with extra care.'],
            ['Emp_ID' => 6, 'Order_ID' => 3, 'Processing_Start' => '2024-06-16 14:15:00', 'Processing_End' => '2024-06-16 14:35:00', 'Status' => 'completed', 'notes' => 'Routine processing.'],
            ['Emp_ID' => 9, 'Order_ID' => 4, 'Processing_Start' => '2024-06-18 09:15:00', 'Processing_End' => '2024-06-18 09:30:00', 'Status' => 'completed', 'notes' => 'Urgent order, fast-tracked.'],
            ['Emp_ID' => 2, 'Order_ID' => 5, 'Processing_Start' => '2024-06-19 10:30:00', 'Processing_End' => '2024-06-19 10:35:00', 'Status' => 'cancelled', 'notes' => 'Order cancelled during processing.'],
            ['Emp_ID' => 6, 'Order_ID' => 6, 'Processing_Start' => '2024-06-19 14:30:00', 'Processing_End' => NULL, 'Status' => 'in-progress', 'notes' => 'Waiting for product arrival.'],
            ['Emp_ID' => 9, 'Order_ID' => 7, 'Processing_Start' => '2024-06-20 16:15:00', 'Processing_End' => NULL, 'Status' => 'in-progress', 'notes' => 'Large volume, ongoing processing.'],
            ['Emp_ID' => 2, 'Order_ID' => 8, 'Processing_Start' => '2024-06-21 11:15:00', 'Processing_End' => '2024-06-21 11:40:00', 'Status' => 'completed', 'notes' => 'Processed for rural delivery.'],
            ['Emp_ID' => 6, 'Order_ID' => 9, 'Processing_Start' => '2024-06-23 08:45:00', 'Processing_End' => '2024-06-23 09:00:00', 'Status' => 'completed', 'notes' => 'Quick processing for next-day.'],
            ['Emp_ID' => 9, 'Order_ID' => 10, 'Processing_Start' => '2024-06-24 10:15:00', 'Processing_End' => NULL, 'Status' => 'in-progress', 'notes' => 'Special handling required, more time needed.'],
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
