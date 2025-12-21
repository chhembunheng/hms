<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('floor_id')->nullable()->after('room_number');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('set null');
        });

        // Migrate existing floor data to floor_id
        DB::statement('UPDATE rooms SET floor_id = (SELECT id FROM floors WHERE floors.floor_number = rooms.floor LIMIT 1) WHERE floor IS NOT NULL');

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('floor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('floor')->nullable()->after('room_number');
        });

        // Migrate back
        DB::statement('UPDATE rooms SET floor = (SELECT floor_number FROM floors WHERE floors.id = rooms.floor_id LIMIT 1) WHERE floor_id IS NOT NULL');

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['floor_id']);
            $table->dropColumn('floor_id');
        });
    }
};
