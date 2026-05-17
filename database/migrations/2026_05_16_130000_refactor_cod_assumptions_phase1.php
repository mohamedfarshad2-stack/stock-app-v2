<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cod_assumptions')) {
            return;
        }

        Schema::table('cod_assumptions', function (Blueprint $table) {
            if (! Schema::hasColumn('cod_assumptions', 'delivery_charge')) {
                $table->decimal('delivery_charge', 12, 2)->default(350)->after('business_unit_id');
            }

            if (! Schema::hasColumn('cod_assumptions', 'return_charge')) {
                $table->decimal('return_charge', 12, 2)->default(175)->after('delivery_charge');
            }

            if (! Schema::hasColumn('cod_assumptions', 'expected_return_percentage')) {
                $table->decimal('expected_return_percentage', 5, 2)->default(30)->after('return_charge');
            }
        });

        Schema::table('cod_assumptions', function (Blueprint $table) {
            $drop = [];
            foreach (['expected_recovery_percentage', 'expected_recovery_cost', 'default_cod_margin_percentage', 'expected_return_courier_cost'] as $column) {
                if (Schema::hasColumn('cod_assumptions', $column)) {
                    $drop[] = $column;
                }
            }
            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('cod_assumptions')) {
            return;
        }

        Schema::table('cod_assumptions', function (Blueprint $table) {
            foreach (['delivery_charge', 'return_charge'] as $column) {
                if (Schema::hasColumn('cod_assumptions', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (! Schema::hasColumn('cod_assumptions', 'expected_return_courier_cost')) {
                $table->decimal('expected_return_courier_cost', 12, 2)->default(175)->after('expected_return_percentage');
            }
            if (! Schema::hasColumn('cod_assumptions', 'expected_recovery_percentage')) {
                $table->decimal('expected_recovery_percentage', 5, 2)->default(40)->after('expected_return_courier_cost');
            }
            if (! Schema::hasColumn('cod_assumptions', 'expected_recovery_cost')) {
                $table->decimal('expected_recovery_cost', 12, 2)->default(30)->after('expected_recovery_percentage');
            }
            if (! Schema::hasColumn('cod_assumptions', 'default_cod_margin_percentage')) {
                $table->decimal('default_cod_margin_percentage', 5, 2)->nullable()->after('expected_recovery_cost');
            }
        });
    }
};
