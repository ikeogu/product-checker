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
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('category')->nullable(); // food, drink, drug
            $table->string('barcode')->nullable()->unique();
            $table->string('nafdac_number')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->default('pending');
            $table->text('description')->nullable();

            // Drug-specific fields
            $table->string('active_ingredient')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('strength')->nullable();
            $table->string('packaging')->nullable();
            $table->boolean('prescription_required')->default(false);

            // Food/Drink-specific fields
            $table->string('volume_or_weight')->nullable();
            $table->json('nutritional_info')->nullable();
            $table->string('flavour')->nullable();
            $table->string('storage_instructions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
