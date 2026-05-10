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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['men','women']);         // section split
            $table->string('item_code')->unique();           // SKU
            $table->string('title');
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('image_path')->nullable();        // storage/app/public/...
            $table->timestamps();

            $table->index(['gender','title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
