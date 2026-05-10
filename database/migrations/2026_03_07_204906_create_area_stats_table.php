<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_stats', function (Blueprint $table) {
            $table->id();

            $table->string('district')->index();
            $table->string('city')->nullable()->index();

            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('delivered_orders')->default(0);
            $table->unsignedInteger('returned_orders')->default(0);

            $table->decimal('return_rate', 5, 2)->default(0);
            $table->string('risk_level', 20)->default('normal')->index();

            $table->timestamps();

            $table->unique(['district', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area_stats');
    }
};
