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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('TRN');  /** Transaction Receipt Number */
            $table->string('type');
            $table->foreignId('account_id')->constrained(table: 'accounts', indexName: 'ledgers_account_id')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->float('amount');
            $table->string('party_code'); /** Store in here (Supplier, Buyer, Investor, Bank, loan) */
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'ledgers_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'ledgers_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'ledgers_updator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
