<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->index('estado', 'contratos_estado_idx');
            $table->index('fecha_fin', 'contratos_fecha_fin_idx');
            $table->index('created_by', 'contratos_created_by_idx');
            $table->index(['estado', 'fecha_fin'], 'contratos_estado_fecha_fin_idx');
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->index('estado', 'documentos_estado_idx');
            $table->index('uploaded_by', 'documentos_uploaded_by_idx');
            $table->index(['contrato_id', 'estado'], 'documentos_contrato_estado_idx');
        });

        Schema::table('tareas', function (Blueprint $table) {
            $table->index('assigned_to', 'tareas_assigned_to_idx');
            $table->index('estado', 'tareas_estado_idx');
            $table->index('fecha_limite', 'tareas_fecha_limite_idx');
            $table->index(['assigned_to', 'estado'], 'tareas_assigned_estado_idx');
        });

        Schema::table('auditorias', function (Blueprint $table) {
            $table->index('contrato_id', 'auditorias_contrato_id_idx');
            $table->index('created_at', 'auditorias_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropIndex('contratos_estado_idx');
            $table->dropIndex('contratos_fecha_fin_idx');
            $table->dropIndex('contratos_created_by_idx');
            $table->dropIndex('contratos_estado_fecha_fin_idx');
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropIndex('documentos_estado_idx');
            $table->dropIndex('documentos_uploaded_by_idx');
            $table->dropIndex('documentos_contrato_estado_idx');
        });

        Schema::table('tareas', function (Blueprint $table) {
            $table->dropIndex('tareas_assigned_to_idx');
            $table->dropIndex('tareas_estado_idx');
            $table->dropIndex('tareas_fecha_limite_idx');
            $table->dropIndex('tareas_assigned_estado_idx');
        });

        Schema::table('auditorias', function (Blueprint $table) {
            $table->dropIndex('auditorias_contrato_id_idx');
            $table->dropIndex('auditorias_created_at_idx');
        });
    }
};
