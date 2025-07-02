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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_factura');
            $table->decimal('monto_total', 10, 2);
            $table->enum('estado', ['pagada', 'pendiente', 'cancelada'])->default('pendiente');

            // FK
            $table->foreignId('id_cliente')->constrained('clients')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
