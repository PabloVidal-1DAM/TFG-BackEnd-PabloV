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
        Schema::create('item_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null'); // Si se borrase un producto en el futuro, no eliminaría el registro de esta tabla al no tener puesto el delete on cascade.
            $table->integer('cantidad');
            $table->decimal('precio_historico', 8, 2); // Actua como un backup del precio del producto, si en un futuro se cambiase el precio del producto, este no cambiaría, así no afectarían a las facturas antiguas.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pedidos');
    }
};
