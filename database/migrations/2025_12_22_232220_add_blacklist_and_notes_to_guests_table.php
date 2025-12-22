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
        Schema::table('guests', function (Blueprint $table) {
            $table->boolean('is_blacklisted')->default(false)->after('notes');
            $table->text('blacklist_reason')->nullable()->after('is_blacklisted');
            $table->timestamp('blacklisted_at')->nullable()->after('blacklist_reason');
            $table->text('internal_notes')->nullable()->after('blacklisted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['is_blacklisted', 'blacklist_reason', 'blacklisted_at', 'internal_notes']);
        });
    }
};
