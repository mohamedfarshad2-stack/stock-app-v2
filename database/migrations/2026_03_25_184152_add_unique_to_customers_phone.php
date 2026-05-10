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
        Schema::table('customers_phone', function (Blueprint $table) {
             Schema::table('customers', function (Blueprint $table) {
        $table->unique('normalized_phone');
    });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers_phone', function (Blueprint $table) {
            //
        });
    }
};
