<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos')->onDelete('cascade');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('numero_version');
            $table->string('archivo');
            $table->string('nombre_original')->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('tamano')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->unique(['documento_id', 'numero_version']);
        });

        DB::table('documentos')
            ->orderBy('id')
            ->select(['id', 'uploaded_by', 'archivo', 'nombre_original', 'created_at', 'updated_at'])
            ->get()
            ->each(function ($documento) {
                DB::table('documento_versiones')->insert([
                    'documento_id' => $documento->id,
                    'uploaded_by' => $documento->uploaded_by,
                    'numero_version' => 1,
                    'archivo' => $documento->archivo,
                    'nombre_original' => $documento->nombre_original,
                    'extension' => pathinfo($documento->archivo, PATHINFO_EXTENSION) ?: null,
                    'tamano' => null,
                    'observacion' => 'Version inicial migrada desde el documento actual.',
                    'created_at' => $documento->created_at,
                    'updated_at' => $documento->updated_at,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_versiones');
    }
};
