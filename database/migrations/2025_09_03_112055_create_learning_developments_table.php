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
        Schema::create('learning_development', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            $table->text('training_title');
            $table->date('inclusive_date_from')->nullable();
            $table->date('inclusive_date_to')->nullable();
            $table->integer('number_of_hours')->nullable();
            $table->text('type_of_id')->nullable();
            $table->text('conducted_by')->nullable();
            $table->timestamps();

            $table->foreign('personal_information_id')
                ->references('id')
                ->on('personal_informations')
                ->onDelete('cascade'); // delete trainings if personal info is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_development');
    }
};
