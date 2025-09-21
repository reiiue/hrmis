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
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_information_id');
            $table->string('nature_type'); // e.g. Personal Loan, Mortgage
            $table->string('name_of_creditors'); // Creditor's name (bank, company, etc.)
            $table->decimal('outstanding_balance', 15, 2); // Amount in pesos
            $table->year('reporting_year'); // SALN reporting year

            $table->timestamps();

            // Foreign key
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
        Schema::dropIfExists('liabilities');
    }
};
