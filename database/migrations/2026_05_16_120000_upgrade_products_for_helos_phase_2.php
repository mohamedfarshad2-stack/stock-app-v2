<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('business_unit_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('sku')->nullable()->after('business_unit_id');
            $table->string('name')->nullable()->after('sku');
            $table->decimal('selling_price', 12, 2)->default(0)->after('name');
            $table->decimal('product_cost', 12, 2)->default(0)->after('selling_price');
            $table->decimal('expected_courier_cost', 12, 2)->nullable()->after('product_cost');
            $table->decimal('weight', 8, 2)->nullable()->after('expected_courier_cost');
            $table->boolean('is_active')->default(true)->after('weight');
            $table->text('notes')->nullable()->after('is_active');
        });

        DB::table('products')->update([
            'sku' => DB::raw('item_code'),
            'name' => DB::raw('title'),
            'product_cost' => DB::raw('cost'),
        ]);

        Schema::table('products', function (Blueprint $table) {
            $table->unique('sku');
            $table->index('business_unit_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropIndex(['business_unit_id']);
            $table->dropIndex(['is_active']);
            $table->dropConstrainedForeignId('business_unit_id');
            $table->dropColumn([
                'sku',
                'name',
                'selling_price',
                'product_cost',
                'expected_courier_cost',
                'weight',
                'is_active',
                'notes',
            ]);
        });
    }
};
