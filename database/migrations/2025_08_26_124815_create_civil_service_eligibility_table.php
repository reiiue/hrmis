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
        Schema::create('civil_service_eligibility', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('personal_information_id'); // Foreign Key
            $table->string('eligibility_type'); 
            $table->string('rating')->nullable();
            $table->date('exam_date')->nullable();
            $table->string('exam_place')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_validity')->nullable();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('personal_information_id')
                  ->references('id')
                  ->on('personal_informations')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('civil_service_eligibility');
    }
};
