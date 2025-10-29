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
        Schema::create('pds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // employee
            $table->enum('status', ['not_started','in_progress','submitted','approved','rejected'])->default('not_started');
            $table->foreignId('last_action_by')->nullable()->constrained('users'); // HR/Admin
            $table->timestamp('last_action_at')->nullable();
            $table->json('data')->nullable(); // optional snapshot of PDS
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds');
    }
};
