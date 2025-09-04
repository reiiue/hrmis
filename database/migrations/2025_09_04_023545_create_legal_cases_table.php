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
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')->constrained()->onDelete('cascade');
            $table->enum('has_admin_offense', ['yes', 'no'])->nullable();
            $table->text('offense_details')->nullable();
            $table->enum('has_criminal_case', ['yes', 'no'])->nullable(); // corrected spelling
            $table->date('date_filed')->nullable();
            $table->text('status_of_case')->nullable();
            $table->enum('has_been_convicted', ['yes', 'no'])->nullable();
            $table->text('conviction_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_cases');
    }
};
