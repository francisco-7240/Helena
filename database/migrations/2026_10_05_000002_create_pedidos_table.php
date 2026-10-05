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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono', 20);
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('codigo_postal', 10)->nullable();
            $table->text('notas')->nullable();
            $table->enum('metodo_pago', ['transferencia', 'contra_entrega'])->default('transferencia');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('envio', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->timestamps();

            $table->index('estado');
            $table->index('email');
        });

        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->foreignId('producto_color_id')->nullable()->constrained('producto_colores')->nullOnDelete();
            $table->string('nombre_producto');
            $table->string('nombre_color')->nullable();
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('cantidad');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
        Schema::dropIfExists('pedidos');
    }
};
