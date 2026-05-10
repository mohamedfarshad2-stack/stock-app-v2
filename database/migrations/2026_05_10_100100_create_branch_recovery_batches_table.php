<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_recovery_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_branch_id')->constrained('courier_branches')->cascadeOnDelete();
            $table->unsignedBigInteger('client_user_id')->nullable()->index();
            $table->date('batch_date')->index();
            $table->string('status', 30)->default('draft')->index(); // draft, sent, acknowledged, acted, stalled, escalated, closed
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('acknowledged_at')->nullable();
            $table->dateTime('acted_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['courier_branch_id', 'batch_date']);
            $table->index(['status', 'batch_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_recovery_batches');
    }
};
