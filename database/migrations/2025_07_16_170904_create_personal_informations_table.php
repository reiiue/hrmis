<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('citizenship', ['Filipino', 'Dual Citizenship']);
            $table->enum('dual_citizenship_type', ['By Birth', 'By Naturalization'])->nullable();
            $table->unsignedBigInteger('dual_citizenship_country_id')->nullable();
            $table->enum('sex', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Separated', 'Others']);
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])->nullable();
            $table->string('position')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('agency_employee_no')->nullable();
            $table->timestamps();

            $table->foreign('dual_citizenship_country_id')->references('id')->on('countries')->nullOnDelete();
        });
    }

};
