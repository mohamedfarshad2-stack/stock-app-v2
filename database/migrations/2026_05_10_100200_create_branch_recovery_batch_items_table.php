<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_recovery_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_recovery_batch_id')->constrained('branch_recovery_batches')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('action_required', 50)->nullable()->index();
            $table->string('item_status', 30)->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['branch_recovery_batch_id', 'order_id'], 'batch_order_unique');
            $table->index(['order_id', 'item_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_recovery_batch_items');
    }
};
