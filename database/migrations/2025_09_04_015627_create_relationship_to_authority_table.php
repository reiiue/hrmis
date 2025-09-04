<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relationship_to_authority', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade');
            $table->enum('within_third_degree', ['yes', 'no'])->default('no');
            $table->enum('within_fourth_degree', ['yes', 'no'])->default('no');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relationship_to_authority');
    }
};
