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
            $table->string('visa_number', 50)->nullable()->after('passport');
            $table->string('visa_type', 30)->nullable()->after('visa_number'); // e.g. T (Tourist), E (Ordinary/Business), etc.
            $table->date('visa_expiry_date')->nullable()->after('visa_type');
            $table->date('entry_date')->nullable()->after('visa_expiry_date');
            $table->string('entry_port', 100)->nullable()->after('entry_date'); // e.g. SAI Airport, Phnom Penh, Poipet, Bavet
        });

        Schema::table('check_ins', function (Blueprint $table) {
            $table->string('guest_visa_number', 50)->nullable()->after('guest_passport');
            $table->string('guest_visa_type', 30)->nullable()->after('guest_visa_number');
            $table->date('guest_visa_expiry_date')->nullable()->after('guest_visa_type');
            $table->date('guest_entry_date')->nullable()->after('guest_visa_expiry_date');
            $table->string('guest_entry_port', 100)->nullable()->after('guest_entry_date');
            $table->string('booking_source', 50)->default('walk_in')->after('billing_type'); // walk_in, booking_com, agoda, expedia, direct, travel_agent
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn([
                'visa_number',
                'visa_type',
                'visa_expiry_date',
                'entry_date',
                'entry_port',
            ]);
        });

        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropColumn([
                'guest_visa_number',
                'guest_visa_type',
                'guest_visa_expiry_date',
                'guest_entry_date',
                'guest_entry_port',
                'booking_source',
            ]);
        });
    }
};
