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
        Schema::create('product_feature_detail_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_feature_detail_id')->constrained('product_feature_details', 'id', 'product_feature_detail_foreign_id')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->text('description')->nullable();
            $table->authors();

            $table->unique(['product_feature_detail_id', 'locale'], 'unique_product_feature_detail_locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_feature_detail_translations');
    }
};
