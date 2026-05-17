<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recovery_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('actor_type', 30)->index(); // system, agent, branch, customer
            $table->string('channel', 30)->index(); // whatsapp, call, link, manual
            $table->string('outcome_code', 50)->index();
            $table->text('outcome_note')->nullable();
            $table->dateTime('attempted_at')->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['order_id', 'attempted_at']);
            $table->index(['actor_type', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_attempts');
    }
};
