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
        Schema::create('patient_health_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->nullable()->constrained('health_plan');
            $table->foreignId('patient_id')->nullable()->constrained('patient')->onDelete('cascade');
            $table->boolean('is_owner')->default(false);
            $table->foreignId('responsible_id')->nullable()->constrained('patient')->onDelete('cascade'); 
            $table->string('relationship')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_health_plan');
    }
};
