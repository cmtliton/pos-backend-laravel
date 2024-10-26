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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('PRN')->nullable();
            $table->integer('cart_total_quantity');
            $table->float('cart_total_amount');
            $table->boolean('status');
            $table->string('supplier_code')->nullable()->constrained(table: 'suppliers', indexName: 'purchases_supplier_code');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'purchases_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'purchases_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'purchases_updator_id');
            $table->timestamps();
        });

        Schema::create('purchasedetails', function (Blueprint $table) {
            $table->id();
            $table->string('PRN')->nullable();
            $table->foreignId('purchase_id')->constrained(table: 'purchases', indexName: 'purchasedetails_purchase_id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(table: 'products', indexName: 'purchasedetails_product_id');
            $table->float('purchase_price');
            $table->float('mrp');
            $table->integer('quantity');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'purchasedetails_company_id')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('slnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchasedetail_id')->constrained(table: 'purchasedetails', indexName: 'slnos_purchasedetail_id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(table: 'products', indexName: 'slnos_product_id');
            $table->string('slno');
            $table->boolean('status');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'slnos_company_id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('purchasedetails');
        Schema::dropIfExists('slnos');
    }
};
