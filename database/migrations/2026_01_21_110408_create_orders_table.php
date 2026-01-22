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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->bigInteger('customer_phone');
            $table->bigInteger('customer_alt_phone')->nullable();
            $table->longText('customer_address');
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('customization_amount', 5, 2)->default(0)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 10, 2)->default(0)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0)->nullable();
            $table->decimal('final_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
