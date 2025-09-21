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
        Schema::create('relatives_in_gov_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            $table->string('name_of_relative')->nullable();
            $table->string('relationship')->nullable();
            $table->string('position_of_relative')->nullable();
            $table->string('name_of_agency')->nullable();
            $table->boolean('no_relative_in_gov_service')->default(false);
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
        Schema::dropIfExists('relatives_in_gov_service');
    }
};
