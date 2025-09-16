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
        Schema::create('total_costs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('personal_information_id')
                  ->constrained('personal_informations')
                  ->onDelete('cascade');

            $table->decimal('real_properties_total', 15, 2)->default(0);
            $table->decimal('personal_property_total', 15, 2)->default(0);
            $table->decimal('total_assets_costs', 15, 2)->default(0);
            $table->decimal('total_liabilities', 15, 2)->default(0);
            $table->decimal('net_worth', 15, 2)->default(0);

            $table->year('reporting_year')->default(date('Y'));

            $table->timestamps();

            // Ensure only one record per person per reporting year
            $table->unique(['personal_information_id', 'reporting_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_costs');
    }
};
