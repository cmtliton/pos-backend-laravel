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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->foreignId('product_id')->constrained(table: 'products', indexName: 'product_images_product_id');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'product_images_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'product_images_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'product_images_updator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
