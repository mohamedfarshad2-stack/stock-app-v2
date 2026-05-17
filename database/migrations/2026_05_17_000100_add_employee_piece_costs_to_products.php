<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'strap_maker_cost')) {
                $table->decimal('strap_maker_cost', 12, 2)->default(0)->after('product_cost');
            }
            if (! Schema::hasColumn('products', 'stitching_worker_cost')) {
                $table->decimal('stitching_worker_cost', 12, 2)->default(0)->after('strap_maker_cost');
            }
            if (! Schema::hasColumn('products', 'finishing_worker_cost')) {
                $table->decimal('finishing_worker_cost', 12, 2)->default(0)->after('stitching_worker_cost');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $drop = [];
            foreach (['strap_maker_cost', 'stitching_worker_cost', 'finishing_worker_cost'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $drop[] = $column;
                }
            }
            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};
