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
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('room_statuses', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('room_types', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('room_pricing', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('check_ins', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('room_statuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('room_pricing', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
