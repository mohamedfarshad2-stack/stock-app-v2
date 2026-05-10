<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('searched_phone');
            $table->string('normalized_phone')->nullable();

            $table->integer('result_count')->default(0);
            $table->boolean('found')->default(false);

            $table->decimal('delivery_probability', 5, 2)->nullable();
            $table->string('risk_level')->nullable();

            $table->string('search_type')->default('manual'); // manual / auto

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};