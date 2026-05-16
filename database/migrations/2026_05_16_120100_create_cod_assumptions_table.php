<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cod_assumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_unit_id')->constrained()->cascadeOnDelete();
            $table->decimal('expected_return_percentage', 5, 2)->default(35);
            $table->decimal('expected_return_courier_cost', 12, 2)->default(175);
            $table->decimal('expected_recovery_percentage', 5, 2)->default(40);
            $table->decimal('expected_recovery_cost', 12, 2)->default(30);
            $table->decimal('default_cod_margin_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cod_assumptions');
    }
};
