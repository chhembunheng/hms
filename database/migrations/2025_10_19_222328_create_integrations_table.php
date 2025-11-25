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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('slug')->unique()->nullable();
            $table->integer('sort')->default(0);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->author();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
