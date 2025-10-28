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
        Schema::create('saln_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')->constrained()->onDelete('cascade');
            $table->string('signature_of_declarant')->nullable(); // path to uploaded signature image
            $table->string('government_issued_id')->nullable();
            $table->string('id_no')->nullable();
            $table->date('date_issued')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saln_certifications');
    }
};
