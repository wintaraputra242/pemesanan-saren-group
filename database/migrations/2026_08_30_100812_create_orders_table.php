<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->string('customer_name', 150);
            $table->string('customer_phone', 30);
            $table->string('customer_email', 100)->nullable();
            $table->enum('delivery_method', ['PICKUP', 'COURIER'])->default('PICKUP');
            $table->text('delivery_address')->nullable();
            $table->unsignedBigInteger('total_amount');
            $table->enum('status', ['PENDING_PAYMENT', 'FILE_VERIFICATION', 'IN_PRODUCTION', 'FINISHING', 'READY_FOR_PICKUP', 'SHIPPED', 'COMPLETED', 'CANCELLED'])->default('PENDING_PAYMENT');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
