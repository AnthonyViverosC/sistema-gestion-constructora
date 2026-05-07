@extends('layouts.app')
@section('title', 'Crear Contrato')

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
            <span class="text-primary/70 font-medium">Nuevo contrato</span>
        </div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Crear Contrato</h2>
        <p class="text-sm text-primary/50 mt-1">Registra la información general del contrato.</p>
    </div>
    <x-button variant="secondary" :href="route('contratos.index')">Volver</x-button>
@endsection

@section('content')
    <div class="max-w-5xl">
        <form action="{{ route('contratos.store') }}" method="POST">
            @csrf

            <x-card title="Datos del contrato" subtitle="Complete los campos obligatorios antes de guardar.">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-input name="numero_contrato" label="Número de contrato" required />
                    <x-form-input name="fecha_contrato" label="Fecha del contrato" type="date" required />
                    <x-form-input name="cedula_contratista" label="Cédula del contratista" required />
                    <x-form-input name="nombre_contratista" label="Nombre del contratista" required />
                    <x-form-input name="fecha_inicio" label="Fecha de inicio" type="date" />
                    <x-form-input name="fecha_fin" label="Fecha fin" type="date" />
                    <x-form-select name="estado" label="Estado" :options="$estados"
                        :value="EstadoContrato::Activo->value" required />
                    <x-form-select name="etiqueta" label="Etiqueta" :options="$etiquetas" placeholder="Sin etiqueta" />

                    <div class="md:col-span-2 space-y-2">
                        <label for="descripcion" class="block text-sm font-semibold text-primary">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4"
                            class="w-full rounded-lg border border-primary/10 bg-white px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <x-slot:footer>
                    <div class="flex justify-end gap-3">
                        <x-button variant="secondary" :href="route('contratos.index')">Cancelar</x-button>
                        <x-button type="submit">Guardar contrato</x-button>
                    </div>
                </x-slot:footer>
            </x-card>
        </form>
    </div>
@endsection
