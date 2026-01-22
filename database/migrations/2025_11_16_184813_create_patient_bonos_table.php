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
        Schema::create('patient_bonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('bono_id')->constrained('bonos')->onDelete('cascade'); // el bono
            $table->integer('sessions_total');
            $table->integer('sessions_used')->default(0);
            $table->integer('sessions_remaining');
            $table->date('purchase_date');
            $table->date('expiration_date');
            $table->enum('status', ['active', 'completed', 'expired'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_bonos');
    }
};
