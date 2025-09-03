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
        Schema::create('work_experience', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade'); // FK to personal_information
            $table->text('position_title');
            $table->text('department_agency');
            $table->integer('monthly_salary');
            $table->string('salary_grade_step')->nullable();
            $table->string('status_appointment');
            $table->enum('gov_service', ['Y', 'N']);
            $table->date('inclusive_date_from');
            $table->date('inclusive_date_to')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experience');
    }
};
