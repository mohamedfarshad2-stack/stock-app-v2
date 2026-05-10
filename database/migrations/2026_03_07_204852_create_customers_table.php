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
         Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('phone', 30);
            $table->string('normalized_phone', 20)->index();

            $table->string('alternate_phone', 30)->nullable();
            $table->string('normalized_alternate_phone', 20)->nullable()->index();

            $table->string('whatsapp_number', 30)->nullable();
            $table->string('normalized_whatsapp_number', 20)->nullable()->index();

            $table->text('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('district')->nullable()->index();

            $table->timestamp('first_order_at')->nullable();
            $table->timestamp('last_order_at')->nullable();

            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('delivered_orders')->default(0);
            $table->unsignedInteger('returned_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->unsignedInteger('no_answer_count')->default(0);
            $table->unsignedInteger('fake_order_count')->default(0);

            $table->decimal('lifetime_value', 12, 2)->default(0);
            $table->integer('trust_score')->default(0);

            $table->string('risk_level', 20)->default('new')->index(); // new, low, medium, high, very_high
            $table->boolean('is_blacklisted')->default(false)->index();
            $table->text('blacklist_reason')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['normalized_phone', 'is_blacklisted']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
