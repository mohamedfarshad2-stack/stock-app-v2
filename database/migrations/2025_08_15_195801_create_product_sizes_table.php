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
        Schema::create('product_sizes', function (Blueprint $table) {
                    $table->id();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->unsignedSmallInteger('size');           // e.g. 39,40...
        $table->unsignedInteger('quantity')->default(0);
        $table->timestamps();

        $table->unique(['product_id','size']);
        $table->index(['size','quantity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sizes');
    }
};
