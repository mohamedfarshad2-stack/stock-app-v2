<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
stabilize-helos-system-implementation-2b7ny6

stabilize-helos-system-implementation-5zq91q

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'packaging_cost')) {
                    $table->decimal('packaging_cost', 12, 2)->default(0)->after('expected_courier_cost');
                }

                if (! Schema::hasColumn('products', 'advertisement_allocation')) {
                    $table->decimal('advertisement_allocation', 12, 2)->default(0)->after('packaging_cost');
                }

                if (! Schema::hasColumn('products', 'operational_overhead')) {
                    $table->decimal('operational_overhead', 12, 2)->default(0)->after('advertisement_allocation');
                }

                if (! Schema::hasColumn('products', 'return_loss_estimate')) {
                    $table->decimal('return_loss_estimate', 12, 2)->default(0)->after('operational_overhead');
                }
            });
        }

        if (Schema::hasTable('daily_cod_operations')) {
            Schema::table('daily_cod_operations', function (Blueprint $table) {
                if (! Schema::hasColumn('daily_cod_operations', 'order_code')) {
                    $table->string('order_code')->nullable()->after('operation_date');
                }

                if (! Schema::hasColumn('daily_cod_operations', 'status')) {
                    $table->enum('status', ['queued', 'dispatched', 'delivered', 'returned'])->default('queued')->after('expected_profit');
                }

                if (! Schema::hasColumn('daily_cod_operations', 'actual_profit')) {
                    $table->decimal('actual_profit', 12, 2)->nullable()->after('status');
                }

                // index is optional for stabilization; avoid duplicate-index failures in mixed environments
            });
        }
stabilize-helos-system-implementation-2b7ny6


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
stabilize-helos-system-implementation-2b7ny6

stabilize-helos-system-implementation-5zq91q

        if (Schema::hasTable('daily_cod_operations')) {
            Schema::table('daily_cod_operations', function (Blueprint $table) {
                $columns = array_filter([
                    Schema::hasColumn('daily_cod_operations', 'order_code') ? 'order_code' : null,
                    Schema::hasColumn('daily_cod_operations', 'status') ? 'status' : null,
                    Schema::hasColumn('daily_cod_operations', 'actual_profit') ? 'actual_profit' : null,
                ]);

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = array_filter([
                    Schema::hasColumn('products', 'packaging_cost') ? 'packaging_cost' : null,
                    Schema::hasColumn('products', 'advertisement_allocation') ? 'advertisement_allocation' : null,
                    Schema::hasColumn('products', 'operational_overhead') ? 'operational_overhead' : null,
                    Schema::hasColumn('products', 'return_loss_estimate') ? 'return_loss_estimate' : null,
                ]);

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
stabilize-helos-system-implementation-2b7ny6


        Schema::table('daily_cod_operations', function (Blueprint $table) {
            $table->dropIndex(['operation_date', 'status']);
            $table->dropColumn(['order_code', 'status', 'actual_profit']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['packaging_cost', 'advertisement_allocation', 'operational_overhead', 'return_loss_estimate']);
        });

    }
};
