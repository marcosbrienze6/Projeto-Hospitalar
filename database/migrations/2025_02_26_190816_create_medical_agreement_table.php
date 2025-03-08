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
        Schema::create('medical_agreement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained('agreement')->default(1);
            $table->foreignId('doctor_id')->nullable()->constrained('doctor')->onDelete('cascade');
            $table->foreignId('patient_id')->nullable()->constrained('patient')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_agreement');
    }
};
