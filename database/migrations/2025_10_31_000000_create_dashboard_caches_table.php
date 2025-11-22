<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dashboard_caches', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index(); // e.g., widget name, section, etc.
            $table->string('locale', 2)->default('en')->index(); // 'en' or 'km'
            $table->json('payload'); // Cached dashboard data
            $table->timestamp('expires_at')->nullable(); // Optional expiry
            $table->author(); // Macro: created_by, updated_by, deleted_by, timestamps, soft-deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_caches');
    }
};
