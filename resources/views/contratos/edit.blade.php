@extends('layouts.app')
@section('title', 'Editar Contrato')

@php
    use App\Enums\EstadoContrato;
    $estados = collect(EstadoContrato::values())->mapWithKeys(fn ($e) => [$e => $e])->all();
    $etiquetas = collect(['Pendiente', 'Falta firma', 'Falta revisar', 'Completo'])
        ->mapWithKeys(fn ($e) => [$e => $e])->all();
@endphp

@section('header')
    <div>
        <div class="flex items-center gap-2 text-xs text-primary/40 mb-1">
            <a href="{{ route('contratos.index') }}" class="hover:text-primary transition-colors">Contratos</a>
            <span>/</span>
            <a href="{{ route('contratos.show', $contrato) }}" class="hover:text-primary transition-colors">{{ $contrato->numero_contrato }}</a>
            <span>/</span>
            <span class="text-primary/70 font-medium">Editar</span>
        </div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Editar Contrato</h2>
        <p class="text-sm text-primary/50 mt-1">Actualiza la información general del contrato.</p>
    </div>
    <x-button variant="secondary" :href="route('contratos.show', $contrato)">Volver</x-button>
@endsection

@section('content')
    <div class="max-w-5xl">
        <form action="{{ route('contratos.update', $contrato) }}" method="POST">
            @csrf
            @method('PUT')

            <x-card title="Datos del contrato" subtitle="Revise los cambios antes de actualizar.">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-input name="numero_contrato" label="Número de contrato"
                        :value="$contrato->numero_contrato" required />
                    <x-form-input name="fecha_contrato" label="Fecha del contrato" type="date"
                        :value="$contrato->fecha_contrato?->format('Y-m-d')" required />
                    <x-form-input name="cedula_contratista" label="Cédula del contratista"
                        :value="$contrato->cedula_contratista" required />
                    <x-form-input name="nombre_contratista" label="Nombre del contratista"
                        :value="$contrato->nombre_contratista" required />
                    <x-form-input name="fecha_inicio" label="Fecha de inicio" type="date"
                        :value="$contrato->fecha_inicio?->format('Y-m-d')" />
                    <x-form-input name="fecha_fin" label="Fecha fin" type="date"
                        :value="$contrato->fecha_fin?->format('Y-m-d')" />
                    <x-form-select name="estado" label="Estado" :options="$estados"
                        :value="$contrato->estado" required />
                    <x-form-select name="etiqueta" label="Etiqueta" :options="$etiquetas"
                        :value="$contrato->etiqueta" placeholder="Sin etiqueta" />

                    <div class="md:col-span-2 space-y-2">
                        <label for="descripcion" class="block text-sm font-semibold text-primary">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4"
                            class="w-full rounded-lg border border-primary/10 bg-white px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">{{ old('descripcion', $contrato->descripcion) }}</textarea>
                    </div>
                </div>

                <x-slot:footer>
                    <div class="flex justify-end gap-3">
                        <x-button variant="secondary" :href="route('contratos.show', $contrato)">Cancelar</x-button>
                        <x-button type="submit">Actualizar contrato</x-button>
                    </div>
                </x-slot:footer>
            </x-card>
        </form>
    </div>
@endsection
