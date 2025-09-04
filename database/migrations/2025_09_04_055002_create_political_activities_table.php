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
        Schema::create('political_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade');
            $table->enum('has_been_candidate', ['yes', 'no'])->default('no');
            $table->text('election_details')->nullable();
            $table->enum('has_resigned_for_campaigning', ['yes', 'no'])->default('no');
            $table->text('campaign_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('political_activities');
    }
};
