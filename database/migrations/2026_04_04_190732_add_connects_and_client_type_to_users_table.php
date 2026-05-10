<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('connects')->default(0);
            $table->string('client_type')->default('basic'); // basic / gold / premium
            $table->integer('role')->default(2); // 1 = admin, 2 = normal user
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['connects', 'client_type', 'role']);
        });
    }
};