<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->unique(); // Country name (e.g., Philippines)
            $table->timestamps(); // created_at and updated_at
        });
    }

};
