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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('email', 255);
            $table->string('telefono', 20)->nullable();
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'respondido', 'archivado'])->default('pendiente');
            $table->timestamps();

            $table->index('email');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
