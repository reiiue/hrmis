<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            
            // Type: reference personal_information or spouse
            $table->enum('type', ['personal', 'spouse']);
            
            // Foreign key to personal_information (optional, nullable if type = spouse)
            $table->foreignId('personal_information_id')->nullable()
                  ->constrained('personal_informations')
                  ->onDelete('cascade');

            // Foreign key to spouse (optional, nullable if type = personal)
            $table->foreignId('spouse_id')->nullable()
                  ->constrained('spouses')
                  ->onDelete('cascade');

            $table->string('name');
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agencies');
    }
};
