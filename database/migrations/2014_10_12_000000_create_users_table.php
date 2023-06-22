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
        Schema::create('users', function (Blueprint $table) {
            // personal account information
            $table->id();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('nationality')->nullable()->default('Nigerian');
            $table->integer('state_of_origin')->nullable();
            $table->integer('local_government_of_origin')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();            

            // next of kin information
            $table->string('nok_name')->nullable();
            $table->string('nok_phone')->nullable();
            $table->string('nok_email')->nullable();
            $table->string('nok_address')->nullable();
            $table->string('nok_relationship')->nullable();
            
            //official informations
            $table->string('branch_id')->nullable();
            $table->string('department_id')->nullable();


            // account control and access
            $table->enum('role', ["admin",  "secretary", "coordinator", "patron", "member", "treasurer"])->default("member");
            $table->enum('status', ["active", "inactive"])->default("active");            
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
