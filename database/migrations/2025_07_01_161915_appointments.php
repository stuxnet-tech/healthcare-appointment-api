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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('healthcare_professional_id')->constrained('healthcare_professionals')->onDelete('cascade');
            $table->dateTime('appointment_start_time');
            $table->dateTime('appointment_end_time');
            $table->enum('status', ['booked', 'completed', 'cancelled'])->default('booked');
            $table->timestamps();
            
            $table->index(
                ['healthcare_professional_id', 'appointment_start_time', 'appointment_end_time'],
                'hcp_appointment_time_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
