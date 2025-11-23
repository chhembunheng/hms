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
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->nullableMorphs('linked');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->author();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
