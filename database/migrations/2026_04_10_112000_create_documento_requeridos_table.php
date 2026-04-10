<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $plantilla = [
        ['nombre' => 'Contrato firmado', 'categoria' => 'Contrato', 'orden' => 1],
        ['nombre' => 'Acta de inicio', 'categoria' => 'Actos Administrativos', 'orden' => 2],
        ['nombre' => 'Seguridad social', 'categoria' => 'Seguridad Social', 'orden' => 3],
        ['nombre' => 'Soporte de pago', 'categoria' => 'Pagos', 'orden' => 4],
    ];

    public function up(): void
    {
        Schema::create('documento_requeridos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->string('nombre');
            $table->string('categoria');
            $table->boolean('obligatorio')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        $ahora = now();

        DB::table('contratos')
            ->orderBy('id')
            ->pluck('id')
            ->each(function ($contratoId) use ($ahora) {
                foreach ($this->plantilla as $item) {
                    DB::table('documento_requeridos')->insert([
                        'contrato_id' => $contratoId,
                        'nombre' => $item['nombre'],
                        'categoria' => $item['categoria'],
                        'obligatorio' => true,
                        'orden' => $item['orden'],
                        'descripcion' => null,
                        'created_at' => $ahora,
                        'updated_at' => $ahora,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_requeridos');
    }
};
