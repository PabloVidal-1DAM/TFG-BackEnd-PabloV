<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('categoria_producto', function (Blueprint $table){
           $table->id();
           $table->foreignUuid('categoria_id')->constrained('categorias')->onDelete('cascade');
           $table->foreignUuid('producto_id')->constrained('productos')->onDelete('cascade');
           $table->timestamps();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { Schema::dropIfExists('categoria_producto');
    }
};
