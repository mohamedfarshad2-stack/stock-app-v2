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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('channel_id')
                ->constrained('channels')
                ->restrictOnDelete();

            $table->string('external_order_no')->nullable()->index();
            $table->string('source', 50)->default('website')->index(); // website, whatsapp, fb, manual
            $table->string('channel_reference')->nullable();

            $table->dateTime('order_date')->index();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->string('payment_method', 20)->default('cod')->index(); // cod, prepaid
            $table->string('courier_name')->nullable()->index();

            $table->text('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('district')->nullable()->index();

            $table->string('verification_status', 30)->default('pending')->index();
            $table->string('delivery_status', 30)->default('pending')->index();
            $table->string('return_reason')->nullable();

            $table->integer('risk_score')->default(0)->index();
            $table->string('risk_level', 20)->default('new')->index();
            $table->string('recommended_action', 50)->nullable()->index();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('verified_at')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('returned_at')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'order_date']);
            $table->index(['channel_id', 'risk_level']);
            $table->index(['channel_id', 'delivery_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
