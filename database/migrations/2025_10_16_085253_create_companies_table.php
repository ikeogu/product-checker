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
        Schema::create('companies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
             $table->foreignUlid('parent_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->foreignUlid('city_id')->nullable();
            $table->foreignUlid('state_id')->nullable();
            $table->foreignUlid('country_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('industry_type')->nullable();
            $table->boolean('nafdac_registered')->default(false);
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'suspended', 'in-progress', 'active', 'inactive'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignUlid('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
