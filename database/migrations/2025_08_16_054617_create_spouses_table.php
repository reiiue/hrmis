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
        Schema::create('spouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable(); // e.g. Jr, Sr, III
            
            $table->string('occupation')->nullable();
            $table->string(column: 'position')->nullable();
            $table->string('employer_business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('telephone_no')->nullable();

            $table->timestamps();

            // Foreign Key
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
        Schema::dropIfExists('spouses');
    }
};
