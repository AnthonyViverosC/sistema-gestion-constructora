@extends('layouts.app')
@section('title', 'Auditoría')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Auditoría</h2>
        <p class="text-sm text-primary/50 mt-1">Últimas acciones registradas en el sistema.</p>
    </div>
@endsection

@section('content')
    <x-card :padded="false">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-primary/10 border-b border-primary/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Usuario</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Acción</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Módulo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    @forelse ($auditorias as $auditoria)
                        <tr class="hover:bg-primary/[0.02] transition-colors">
                            <td class="px-6 py-4 text-sm text-primary/70 whitespace-nowrap">{{ $auditoria->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->user?->name ?? 'Sistema' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-primary">{{ ucfirst($auditoria->accion) }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ ucfirst($auditoria->modulo) }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->detalle }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-0 py-0">
                                <x-empty-state icon="search" title="No hay acciones registradas"
                                    description="Cuando los usuarios realicen acciones, las verás aquí." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
@endsection
