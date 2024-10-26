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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained(table: 'purchases', indexName: 'stocks_purchase_id')->cascadeOnDelete();
            $table->string('PRN')->nullable();
            $table->bigInteger('purchasedetail_id');
            $table->foreignId('product_id')->constrained(table: 'products', indexName: 'stocks_product_id');
            $table->integer('quantity');
            $table->float('purchase_price');
            $table->float('mrp');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'stocks_company_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
