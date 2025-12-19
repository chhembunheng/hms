<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_pricing', function (Blueprint $table) {
            $table->enum('pricing_type', ['night', '3_hours'])->default('night')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_pricing', function (Blueprint $table) {
            $table->dropColumn('pricing_type');
        });
    }
};
