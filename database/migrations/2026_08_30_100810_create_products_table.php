<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->enum('category', ['CUSTOM_SERVICE', 'PHYSICAL_PRODUCT']);
            $table->text('description')->nullable();
            $table->unsignedInteger('base_price');
            $table->decimal('min_size_m2', 5, 2)->default(0.25)->nullable();
            $table->string('unit_label', 20);
            $table->boolean('is_custom_dimension')->default(false);
            $table->boolean('requires_design_file')->default(false);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
