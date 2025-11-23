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
        Schema::create('search_index', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('model');
            $table->string('locale', 10)->default('en');
            $table->string('title');
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url')->nullable();
            $table->author();

            $table->index(['locale','title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_indices');
    }
};
