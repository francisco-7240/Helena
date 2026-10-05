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
        Schema::table('productos', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('slug');
            $table->decimal('precio', 10, 2)->default(0)->after('descripcion');
            $table->decimal('precio_oferta', 10, 2)->nullable()->after('precio');
            $table->boolean('destacado')->default(false)->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn(['sku', 'precio', 'precio_oferta', 'destacado']);
        });
    }
};
