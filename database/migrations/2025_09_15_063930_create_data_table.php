<?php
// database/migrations/2025_09_15_000000_create_data_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('item_code')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
