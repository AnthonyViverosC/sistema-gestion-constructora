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

<aside id="appSidebar"
    class="w-64 flex-shrink-0 border-r border-primary/10 bg-white flex flex-col transition-all duration-200">
    <div class="p-4 border-b border-primary/10">
        <div class="flex items-center gap-3 mb-3">
            <div class="size-8 bg-primary text-white flex items-center justify-center rounded-lg font-bold text-sm">
                SD
            </div>
            <h1 class="sidebar-label text-primary text-sm font-bold uppercase tracking-wider leading-tight">
                SALAZAR &amp; D&Iacute;AZ S.A.S
            </h1>
        </div>

        <div class="sidebar-label">
            <x-rol-label />
        </div>

        <button type="button" id="toggleSidebar"
            class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg border border-primary/10 bg-white px-3 py-2 text-xs font-bold uppercase tracking-widest text-primary/60 hover:bg-primary/5"
            aria-label="Contraer menú" aria-expanded="true">
            <svg id="toggleSidebarIcon" class="size-4 transition-transform" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="m15 6-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="sidebar-label">Contraer</span>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-6">
        <div class="space-y-1">
            <p class="sidebar-label px-4 text-[11px] font-black uppercase tracking-widest text-primary/35">
                General
            </p>
            <a href="{{ route('dashboard') }}" title="Panel principal" class="sidebar-link {{ $itemClass('dashboard') }}">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6.5 10v9h11v-9" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10 19v-5h4v5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="sidebar-label">Panel principal</span>
            </a>
        </div>

        <div class="space-y-1">
            <p class="sidebar-label px-4 text-[11px] font-black uppercase tracking-widest text-primary/35">
                Gesti&oacute;n documental
            </p>
            <a href="{{ route('contratos.index') }}" title="Contratos" class="sidebar-link {{ $itemClass('contratos.*') }}">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M7 3.5h7l3 3V20a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1Z" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 3.5V7h3" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9 11h6M9 15h6M9 18h3" stroke-linecap="round" />
                </svg>
                <span class="sidebar-label">Contratos</span>
            </a>

            @if ($contratoRuta)
                <a href="{{ route('documentos.create', $contratoRuta) }}" title="Expediente actual" class="sidebar-link {{ $itemClass('documentos.*') }}">
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M4.5 6.5h5l1.5 2h8.5v9.5a1.5 1.5 0 0 1-1.5 1.5h-12A1.5 1.5 0 0 1 4.5 18V6.5Z" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.5 9h15" stroke-linecap="round" />
                        <path d="M9 13h6M9 16h4" stroke-linecap="round" />
                    </svg>
                    <span class="sidebar-label">Expediente actual</span>
                </a>
            @endif

            <a href="{{ route('tareas.index') }}" title="Tareas asignadas" class="sidebar-link {{ $itemClass('tareas.*') }}">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M8.5 6.5h10M8.5 12h10M8.5 17.5h10" stroke-linecap="round" />
                    <path d="m4.5 6.5.8.8 1.7-1.8M4.5 12l.8.8L7 11M4.5 17.5l.8.8 1.7-1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="sidebar-label">Tareas asignadas</span>
            </a>
        </div>

        @if ($puedeGestionar)
            <div class="space-y-1">
                <p class="sidebar-label px-4 text-[11px] font-black uppercase tracking-widest text-primary/35">
                    Administraci&oacute;n
                </p>
                <a href="{{ route('usuarios.index') }}" title="Usuarios y roles" class="sidebar-link {{ $itemClass('usuarios.*') }}">
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M9.5 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.5 19a5 5 0 0 1 10 0" stroke-linecap="round" />
                        <path d="M16 10.5a2.5 2.5 0 1 0 0-5" stroke-linecap="round" />
                        <path d="M17 18.5a4 4 0 0 0-2-3.4" stroke-linecap="round" />
                    </svg>
                    <span class="sidebar-label">Usuarios y roles</span>
                </a>
                <a href="{{ route('auditoria.index') }}" title="Auditor&iacute;a" class="sidebar-link {{ $itemClass('auditoria.*') }}">
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M12 4.5 18 7v5c0 3.7-2.4 6.8-6 7.8-3.6-1-6-4.1-6-7.8V7l6-2.5Z" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.5 12.2 11 13.7l3.5-3.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="sidebar-label">Auditor&iacute;a</span>
                </a>
            </div>
        @endif
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="p-4 border-t border-primary/10">
        @csrf
        <button type="submit"
            class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors text-sm font-semibold"
            title="Cerrar sesi&oacute;n">
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path d="M10 6H6.5A1.5 1.5 0 0 0 5 7.5v9A1.5 1.5 0 0 0 6.5 18H10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 8.5 17.5 12 14 15.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17.5 12H9" stroke-linecap="round" />
            </svg>
            <span class="sidebar-label">Cerrar sesi&oacute;n</span>
        </button>
    </form>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('appSidebar');
        const boton = document.getElementById('toggleSidebar');
        const icono = document.getElementById('toggleSidebarIcon');
        const labels = sidebar?.querySelectorAll('.sidebar-label') ?? [];
        const links = sidebar?.querySelectorAll('.sidebar-link') ?? [];

        if (!sidebar || !boton) return;

        function aplicarEstado(colapsado) {
            sidebar.classList.toggle('w-64', !colapsado);
            sidebar.classList.toggle('w-20', colapsado);
            sidebar.classList.toggle('items-center', colapsado);
            boton.setAttribute('aria-expanded', String(!colapsado));
            boton.setAttribute('aria-label', colapsado ? 'Expandir menú' : 'Contraer menú');
            icono?.classList.toggle('rotate-180', colapsado);

            labels.forEach((label) => {
                label.classList.toggle('hidden', colapsado);
            });

            links.forEach((link) => {
                link.classList.toggle('justify-center', colapsado);
                link.classList.toggle('px-4', !colapsado);
                link.classList.toggle('px-0', colapsado);
            });
        }

        const estadoGuardado = localStorage.getItem('sidebar-colapsado') === 'true';
        aplicarEstado(estadoGuardado);

        boton.addEventListener('click', function() {
            const colapsado = !sidebar.classList.contains('w-20');
            aplicarEstado(colapsado);
            localStorage.setItem('sidebar-colapsado', String(colapsado));
        });
    });
</script>
