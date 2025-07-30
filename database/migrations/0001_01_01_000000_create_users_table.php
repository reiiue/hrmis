<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['Admin', 'Employee', 'HR']);
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('status', ['Active', 'Inactive', 'Suspended', 'Pending', 'Archived'])->default('Pending');
            $table->timestamps();
        });
    }

};
