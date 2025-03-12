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
        Schema::create('appointment', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('code', 12)->unique();
            $table->foreignId('patient_id')->constrained('patient')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctor')->onDelete('cascade');
            $table->foreignId('diagnosis_id')->nullable()->constrained('patient_diagnosis')->onDelete('set null');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment');
    }
};
