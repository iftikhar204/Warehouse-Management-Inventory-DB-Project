<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'company_name', 'default_currency'
            $table->text('value')->nullable(); // The actual setting value
            $table->string('type')->default('string'); // e.g., 'string', 'integer', 'boolean', 'json'
            $table->string('description')->nullable(); // A friendly description for the setting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
