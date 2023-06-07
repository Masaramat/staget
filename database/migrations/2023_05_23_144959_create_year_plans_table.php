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
        Schema::create('year_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->float('min_savings');
            $table->float('year_interest');
            $table->integer('loan_percentage');
            $table->tinyInteger('interest_rate');
            $table->enum('interest_type', ['one_off', 'monthly'])->default('monthly');
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('year_plans');
    }
};
