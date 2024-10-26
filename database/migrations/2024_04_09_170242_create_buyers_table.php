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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('mobileno');
            $table->string('addr')->nullable();
            $table->string('type');
            $table->boolean('status');
            $table->foreignId('company_id')->constrained(table: 'companies', indexName: 'buyers_company_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'buyers_user_id');
            $table->foreignId('updator_id')->nullable()->constrained(table: 'users', indexName: 'buyers_updator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
