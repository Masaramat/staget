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
        Schema::create('external_borrowers', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->string('borrower_name');
            $table->integer('borrower_bvn');
            $table->string('borrower_phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_lenders');
    }
};
