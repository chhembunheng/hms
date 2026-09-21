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
        Schema::create('airport_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_in_id')->nullable()->constrained('check_ins')->nullOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained('guests')->nullOnDelete();
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->enum('transfer_type', ['pickup', 'dropoff'])->default('pickup');
            $table->string('flight_number')->nullable(); // e.g. K6 812, SQ 164, QR 968
            $table->dateTime('transfer_datetime');
            $table->enum('vehicle_type', ['van', 'car', 'remork_tuktuk', 'suv'])->default('van');
            $table->string('pickup_location')->default('Siem Reap–Angkor Int. Airport (SAI)');
            $table->string('dropoff_location')->nullable();
            $table->unsignedInteger('passenger_count')->default(1);
            $table->unsignedInteger('luggage_count')->default(1);
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_charged_to_room')->default(true);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airport_transfers');
    }
};
