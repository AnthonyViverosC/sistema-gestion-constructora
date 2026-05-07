<div id="modalConfirmacion"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 transition-opacity duration-200">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-primary/10 overflow-hidden">
        <div class="px-6 py-5 border-b border-primary/10">
            <h3 id="modalConfirmacionTitulo" class="text-lg font-bold text-primary">Confirmar acción</h3>
            <p id="modalConfirmacionSubtitulo" class="text-sm text-primary/50 mt-1">Por favor confirma para continuar.</p>
        </div>
        <div class="px-6 py-5">
            <p id="modalConfirmacionMensaje" class="text-sm text-primary/70"></p>
            <p id="modalConfirmacionDetalle" class="mt-2 text-base font-bold text-primary"></p>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-primary/10 flex justify-end gap-3">
            <button type="button" onclick="cerrarModalConfirmacion()"
                class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary/70 hover:bg-primary/5 transition-colors">
                Cancelar
            </button>
            <form id="formConfirmacion" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formConfirmacionMethod" value="POST">
                <button type="submit" id="modalConfirmacionBoton"
                    class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                    Confirmar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const modal     = document.getElementById('modalConfirmacion');
    const form      = document.getElementById('formConfirmacion');
    const methodEl  = document.getElementById('formConfirmacionMethod');
    const tituloEl  = document.getElementById('modalConfirmacionTitulo');
    const subEl     = document.getElementById('modalConfirmacionSubtitulo');
    const mensajeEl = document.getElementById('modalConfirmacionMensaje');
    const detalleEl = document.getElementById('modalConfirmacionDetalle');
    const botonEl   = document.getElementById('modalConfirmacionBoton');

    const VARIANTES = {
        primary: 'bg-primary text-white hover:bg-primary/90',
        amber:   'bg-amber-600 text-white hover:bg-amber-700',
        red:     'bg-red-600 text-white hover:bg-red-700',
        green:   'bg-green-600 text-white hover:bg-green-700',
        blue:    'bg-blue-600 text-white hover:bg-blue-700',
        slate:   'bg-slate-600 text-white hover:bg-slate-700',
    };

    window.abrirConfirmacion = function (opciones) {
        opciones = opciones || {};
        form.action       = opciones.action || '#';
        methodEl.value    = (opciones.method || 'POST').toUpperCase();
        tituloEl.textContent  = opciones.titulo    || 'Confirmar acción';
        subEl.textContent     = opciones.subtitulo || 'Por favor confirma para continuar.';
        mensajeEl.textContent = opciones.mensaje   || '';
        detalleEl.textContent = opciones.detalle   || '';
        detalleEl.classList.toggle('hidden', !opciones.detalle);

        botonEl.textContent = opciones.confirmTexto || 'Confirmar';
        botonEl.className   = 'px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors ' +
            (VARIANTES[opciones.color] || VARIANTES.primary);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.cerrarModalConfirmacion = function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    modal.addEventListener('click', e => { if (e.target === modal) cerrarModalConfirmacion(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) cerrarModalConfirmacion();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-confirmar]');
        if (!btn) return;
        e.preventDefault();
        abrirConfirmacion({
            action:       btn.dataset.confirmarAction,
            method:       btn.dataset.confirmarMethod,
            titulo:       btn.dataset.confirmarTitulo,
            subtitulo:    btn.dataset.confirmarSubtitulo,
            mensaje:      btn.dataset.confirmarMensaje,
            detalle:      btn.dataset.confirmarDetalle,
            confirmTexto: btn.dataset.confirmarConfirmTexto,
            color:        btn.dataset.confirmarColor,
        });
    });
})();
</script>
