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
        Schema::create('straps', function (Blueprint $table) {
              $table->id();
            $table->string('title');
            $table->string('item_code')->unique();
            $table->unsignedInteger('quantity')->default(0);
            $table->string('image_path')->nullable(); // stored path in storage/app/public/...
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
        Schema::dropIfExists('straps');
    }
};
