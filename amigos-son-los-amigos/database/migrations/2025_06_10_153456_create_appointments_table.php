<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_turno');
            $table->enum('estado', ['pendiente', 'confirmado', 'cancelado', 'completado'])->default('pendiente');

            // Claves foráneas
            $table->foreignId('id_cliente')->constrained('clients')->onDelete('cascade');
            $table->foreignId('id_mascota')->constrained('pets')->onDelete('cascade');
            $table->foreignId('id_empleado')->constrained('employees')->onDelete('cascade');

            $table->timestamps();
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
