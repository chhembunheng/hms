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
        Schema::create('navigation_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('navigation_id')->constrained('navigations')->onDelete('cascade');
            $table->string('locale', 10);
            $table->string('name');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->author();
            $table->unique(['navigation_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_translations');
    }
};
