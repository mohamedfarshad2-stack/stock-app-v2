<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_branches', function (Blueprint $table) {
            $table->id();
            $table->string('courier_name')->index();
            $table->string('branch_name')->index();
            $table->string('district')->nullable()->index();
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_whatsapp', 30)->nullable();
            $table->string('preferred_mode', 20)->default('both')->index(); // whatsapp, call, both
            $table->boolean('active')->default(true)->index();
            $table->timestamps();

            $table->unique(['courier_name', 'branch_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_branches');
    }
};
