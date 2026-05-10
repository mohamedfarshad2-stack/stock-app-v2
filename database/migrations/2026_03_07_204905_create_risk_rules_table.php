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
        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();

            $table->string('rule_key')->unique(); // delivered_order, returned_order, fake_customer
            $table->string('rule_name');
            $table->integer('score_effect')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risk_rules');
    }
};
