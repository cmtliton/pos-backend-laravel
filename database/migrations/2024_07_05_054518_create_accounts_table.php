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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code'); 
            $table->string('description')->nullable();
        });
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code'); 
            $table->string('description')->nullable();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('account_type_id');
            $table->boolean('status');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'accounts_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'accounts_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'accounts_updator_id');
            $table->timestamps();
        });

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
