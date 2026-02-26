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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cif', 9)->unique(); // Identifica fiscalmente a una empresa.
            $table->string('email')->nullable()->unique(); // El proveedor no está obligado a darlo para que lo vean todos.
            $table->string('telefono')->nullable(); // Misma lógica que email.
            $table->string('direccion')->nullable(); // Misma lógica que email.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
