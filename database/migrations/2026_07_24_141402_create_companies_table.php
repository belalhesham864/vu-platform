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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->unique();
            $table->string('slug')->unique();
           $table->string('industry');
            $table->string('location');
            $table->text('about')->nullable();

            $table->string('phone')->unique();
            $table->string('logo');
            $table->string('website');
            $table->string('company_size');
          $table->enum('status', ['pending','active','suspended'])->default('pending'); 
        //    $table->foreignId('subscription_plan_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
