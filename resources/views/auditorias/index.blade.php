@extends('layouts.app')
@section('title', 'Auditoría')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Auditoría</h2>
        <p class="text-sm text-primary/50 mt-1">Últimas acciones registradas en el sistema.</p>
    </div>
@endsection

@section('content')
    <div>
        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Usuario</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Acción</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Módulo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    @forelse ($auditorias as $auditoria)
                        <tr class="hover:bg-primary/[0.02]">
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->created_at?->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->user?->name ?? 'Sistema' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-primary">{{ ucfirst($auditoria->accion) }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ ucfirst($auditoria->modulo) }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->detalle }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-primary/40">No hay acciones
                                registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
