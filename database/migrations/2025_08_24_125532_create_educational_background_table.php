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
        Schema::create('educational_background', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            $table->enum('level', [
                'Elementary',
                'Secondary',
                'VocationalCourse',
                'College',
                'GraduateStudies'
            ]);
            $table->string('school_name')->nullable();
            $table->string('degree_course')->nullable();
            $table->integer('period_from')->nullable();
            $table->integer('period_to')->nullable();
            $table->string('highest_level_unit_earned')->nullable();
            $table->integer('year_graduated')->nullable();
            $table->string('scholarship_honors')->nullable();
            $table->timestamps();

            // Foreign key constraint
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
        Schema::dropIfExists('educational_background');
    }
};
