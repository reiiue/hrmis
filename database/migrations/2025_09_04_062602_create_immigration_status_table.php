<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immigration_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            $table->enum('has_immigrant_status', ['yes', 'no'])->default('no');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('personal_information_id')
                  ->references('id')->on('personal_informations')
                  ->onDelete('cascade');

            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immigration_status');
    }
};
