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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
             $table->integer('user_id');
             $table->integer('year_id');
             $table->double('amount_applied');
             $table->enum('loan_type', ['internal', ['external']])->default('internal');
             $table->double('amount_approved')->default(0.00);
             $table->double('balance')->default(0.00);
             $table->integer('tenor');
             $table->integer('tenor_approved');
             $table->string('tenor_type');
             $table->enum('repayment_type', ['flat upfront interest', 'flat', 'balloon upfront interest', 'balloon']);
             $table->double('installments')->default(0.00);
             $table->double('interest_paid')->default(0.00);
             $table->timestamp('maturity');
             $table->string('application_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
