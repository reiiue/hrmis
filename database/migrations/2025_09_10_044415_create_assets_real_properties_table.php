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
        Schema::create('assets_real_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            
            $table->string('description')->nullable();
            $table->string('kind')->nullable();
            $table->string('location')->nullable();
            $table->decimal('assessed_value', 15, 2)->nullable();
            $table->decimal('current_fair_market_value', 15, 2)->nullable();
            $table->year('acquisition_year')->nullable();
            $table->string('acquisition_mode')->nullable();
            $table->decimal('acquisition_cost', 15, 2)->nullable();
            $table->year('reporting_year'); // Reporting year for SALN

            $table->timestamps();

            $table->foreign('personal_information_id')
                ->references('id')->on('personal_informations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_real_properties');
    }
};
