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
        Schema::create('business_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')->constrained()->onDelete('cascade');
            $table->string('name_of_business')->nullable();
            $table->string('business_address')->nullable();
            $table->string('name_of_business_interest')->nullable();
            $table->date('date_of_acquisition')->nullable();
            $table->boolean('no_business_interest')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_interests');
    }
};
