<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_cod_operations', function (Blueprint $table) {
            $table->id();
            $table->date('operation_date');
            $table->foreignId('business_unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('selling_price', 12, 2);
            $table->decimal('product_cost', 12, 2);
            $table->decimal('courier_cost', 12, 2);
            $table->decimal('expected_return_percentage', 5, 2);
            $table->decimal('expected_profit', 12, 2)->nullable();
            $table->integer('delivered_quantity')->default(0);
            $table->integer('returned_quantity')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('operation_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_cod_operations');
    }
};
