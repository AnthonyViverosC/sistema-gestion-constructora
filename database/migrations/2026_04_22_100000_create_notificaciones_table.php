<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tarea_id')->nullable()->constrained('tareas')->nullOnDelete();
            $table->string('tipo', 100);
            $table->string('canal', 50)->default('sistema');
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('estado', 50)->default('pendiente');
            $table->timestamp('fecha_evento')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
