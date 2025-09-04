<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade');
            
            $table->enum('is_indigenous_member', ['yes', 'no'])->default('no');
            $table->text('indigenous_group_name')->nullable();

            $table->enum('is_person_with_disability', ['yes', 'no'])->default('no');
            $table->unsignedBigInteger('pwd_id_number')->nullable();

            $table->enum('is_solo_parent', ['yes', 'no'])->default('no');
            $table->unsignedBigInteger('solo_parent_id_number')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_status');
    }
};
