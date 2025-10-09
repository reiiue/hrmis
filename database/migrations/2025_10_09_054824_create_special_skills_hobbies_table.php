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
        Schema::create('special_skills_hobbies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            $table->string('special_skills')->nullable();
            $table->string('non_academic_distinctions')->nullable();
            $table->string('membership_in_organization')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('special_skills_hobbies');
    }
};
