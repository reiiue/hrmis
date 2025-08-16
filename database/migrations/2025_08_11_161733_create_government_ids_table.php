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
        Schema::create('government_ids', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('personal_information_id'); // Foreign Key

            $table->string('gsis_id')->nullable();
            $table->string('pagibig_id')->nullable();
            $table->string('philhealth_id')->nullable();
            $table->string('sss_id')->nullable();
            $table->string('tin_id')->nullable();

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
        Schema::dropIfExists('government_ids');
    }
};
