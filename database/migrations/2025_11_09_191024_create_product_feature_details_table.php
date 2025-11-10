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
        Schema::create('product_feature_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_feature_id')->constrained('product_features')->cascadeOnDelete();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->authors();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_feature_details');
    }
};
