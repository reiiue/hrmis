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
        Schema::create('assets_personal_properties', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade'); // Foreign key reference
            $table->string('description'); // Description of property
            $table->year('year_acquired')->nullable(); // Year acquired
            $table->decimal('acquisition_cost', 15, 2)->nullable(); // Acquisition cost
            $table->year('reporting_year'); // Reporting year for SALN
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_personal_properties');
    }
};
