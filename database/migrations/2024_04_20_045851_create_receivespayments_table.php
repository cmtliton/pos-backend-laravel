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
        Schema::create('receivespayments', function (Blueprint $table) {
            $table->id();
            $table->string('description_id');
            $table->string('description')->nullable();
            $table->string('type');
            $table->float('amount');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'receivespayments_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'receivespayments_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'receivespayments_updator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivespayments');
    }
};
