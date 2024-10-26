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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('inv');
            $table->integer('cart_total_quantity');
            $table->float('cart_total_amount');
            $table->float('discount');
            $table->float('shipping');
            $table->float('vat');
            $table->float('tax');
            $table->boolean('status');
            $table->string('buyer_code')->constrained(table: 'buyers', indexName: 'sales_buyer_code');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'sales_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'sales_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'sales_updator_id');
            $table->timestamps();
        });

        Schema::create('saledetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained(table: 'sales', indexName: 'saledetails_sale_id')->cascadeOnDelete();
            $table->string('sale_inv');
            $table->foreignId('product_id')->constrained(table: 'products', indexName: 'saledetails_product_id');
            $table->integer('quantity');
            $table->float('purchase_price');
            $table->float('mrp');
            $table->string('vat_type')->nullable();
            $table->float('vat')->nullable();
            $table->string('tax_type')->nullable();
            $table->float('tax')->nullable();
            $table->string('disc_type')->nullable();
            $table->float('discount')->nullable();
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'saledetails_company_id')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saledetail_id')->constrained(table: 'saledetails', indexName: 'deliveries_saledetail_id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(table: 'products', indexName: 'deliveries_product_id');
            $table->integer('quantity');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'deliveries_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'deliveries_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'deliveries_updator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('saledetails');
        Schema::dropIfExists('deliveries');
    }
};
