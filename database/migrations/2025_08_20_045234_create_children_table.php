<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade'); // delete children if personal_info is deleted
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_living_with_declarant')->default(false); // ✅ new column
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
