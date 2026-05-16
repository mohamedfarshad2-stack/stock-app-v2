<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('packaging_cost', 12, 2)->default(0)->after('expected_courier_cost');
            $table->decimal('advertisement_allocation', 12, 2)->default(0)->after('packaging_cost');
            $table->decimal('operational_overhead', 12, 2)->default(0)->after('advertisement_allocation');
            $table->decimal('return_loss_estimate', 12, 2)->default(0)->after('operational_overhead');
        });

        Schema::table('daily_cod_operations', function (Blueprint $table) {
            $table->string('order_code')->nullable()->after('operation_date');
            $table->enum('status', ['queued', 'dispatched', 'delivered', 'returned'])->default('queued')->after('expected_profit');
            $table->decimal('actual_profit', 12, 2)->nullable()->after('status');
            $table->index(['operation_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('daily_cod_operations', function (Blueprint $table) {
            $table->dropIndex(['operation_date', 'status']);
            $table->dropColumn(['order_code', 'status', 'actual_profit']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['packaging_cost', 'advertisement_allocation', 'operational_overhead', 'return_loss_estimate']);
        });
    }
};
