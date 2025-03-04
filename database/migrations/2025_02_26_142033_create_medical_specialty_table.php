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
        Schema::create('medical_specialty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->constrained('specialty')->default(1);
            $table->foreignId('doctor_id')->constrained('doctor');
            $table->foreignId('patient_id')->constrained('patient');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_speacialty');
    }
};
