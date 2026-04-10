@props([
    'contrato' => null,
    'documento' => null,
])

@php
    $usuario = auth()->user();
    $rol = $usuario?->rol;
    $puedeGestionar = in_array($rol, ['admin', 'gestor']);
    $contratoContexto = $contrato ?? null;
    $contratoDocumento = $documento?->contrato_id ?? null;
    $contratoRuta = $contratoContexto ?? $contratoDocumento;

    $linkBase = 'flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition-colors';
    $linkActivo = 'bg-primary text-white shadow-sm';
    $linkInactivo = 'text-primary/70 hover:bg-primary/5 hover:text-primary';

    $itemClass = fn (string $patron) => $linkBase.' '.(request()->routeIs($patron) ? $linkActivo : $linkInactivo);
@endphp

<aside class="w-64 flex-shrink-0 border-r border-primary/10 bg-white flex flex-col">
    <div class="p-6 border-b border-primary/10">
        <div class="flex items-center gap-3 mb-1">
            <div class="size-8 bg-primary text-white flex items-center justify-center rounded-lg font-bold text-sm">
                SD
            </div>
            <h1 class="text-primary text-sm font-bold uppercase tracking-wider leading-tight">
                SALAZAR &amp; D&Iacute;AZ S.A.S
            </h1>
        </div>
        <x-rol-label />
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-6">
        <div class="space-y-1">
            <p class="px-4 text-[11px] font-black uppercase tracking-widest text-primary/35">
                General
            </p>
            <a href="{{ route('dashboard') }}" class="{{ $itemClass('dashboard') }}">
                <span class="w-5 text-center text-[11px] font-black">IN</span>
                <span>Panel principal</span>
            </a>
        </div>

        <div class="space-y-1">
            <p class="px-4 text-[11px] font-black uppercase tracking-widest text-primary/35">
                Gesti&oacute;n documental
            </p>
            <a href="{{ route('contratos.index') }}" class="{{ $itemClass('contratos.*') }}">
                <span class="w-5 text-center text-[11px] font-black">CT</span>
                <span>Contratos</span>
            </a>

            @if ($contratoRuta)
                <a href="{{ route('documentos.create', $contratoRuta) }}" class="{{ $itemClass('documentos.*') }}">
                    <span class="w-5 text-center text-[11px] font-black">EX</span>
                    <span>Expediente actual</span>
                </a>
            @endif

            <a href="{{ route('tareas.index') }}" class="{{ $itemClass('tareas.*') }}">
                <span class="w-5 text-center text-[11px] font-black">TA</span>
                <span>Tareas asignadas</span>
            </a>
        </div>

        @if ($puedeGestionar)
            <div class="space-y-1">
                <p class="px-4 text-[11px] font-black uppercase tracking-widest text-primary/35">
                    Administraci&oacute;n
                </p>
                <a href="{{ route('usuarios.index') }}" class="{{ $itemClass('usuarios.*') }}">
                    <span class="w-5 text-center text-[11px] font-black">US</span>
                    <span>Usuarios y roles</span>
                </a>
                <a href="{{ route('auditoria.index') }}" class="{{ $itemClass('auditoria.*') }}">
                    <span class="w-5 text-center text-[11px] font-black">AU</span>
                    <span>Auditor&iacute;a</span>
                </a>
            </div>
        @endif
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="p-4 border-t border-primary/10">
        @csrf
        <button type="submit"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors text-sm font-semibold">
            <span class="w-5 text-center text-[11px] font-black">SA</span>
            <span>Cerrar sesi&oacute;n</span>
        </button>
    </form>
</aside>
