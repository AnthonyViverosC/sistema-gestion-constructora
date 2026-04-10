<div id="toastContainer" class="fixed top-5 right-5 z-[9999] space-y-3 w-full max-w-sm pointer-events-none">
    @if (session('success'))
        <div class="toast pointer-events-auto rounded-xl border border-green-200 bg-white shadow-lg overflow-hidden">
            <div class="flex items-start gap-3 p-4">
                <div
                    class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 font-bold">
                    ✓
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-800">Operación exitosa</p>
                    <p class="text-sm text-slate-600 mt-1">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="cerrarToast(this)"
                    class="text-slate-400 hover:text-slate-700 text-lg leading-none">
                    ×
                </button>
            </div>
            <div class="h-1 bg-green-500 toast-bar"></div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast pointer-events-auto rounded-xl border border-red-200 bg-white shadow-lg overflow-hidden">
            <div class="flex items-start gap-3 p-4">
                <div
                    class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600 font-bold">
                    !
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-800">Ocurrió un problema</p>
                    <p class="text-sm text-slate-600 mt-1">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="cerrarToast(this)"
                    class="text-slate-400 hover:text-slate-700 text-lg leading-none">
                    ×
                </button>
            </div>
            <div class="h-1 bg-red-500 toast-bar"></div>
        </div>
    @endif

    @if ($errors->any())
        <div class="toast pointer-events-auto rounded-xl border border-amber-200 bg-white shadow-lg overflow-hidden">
            <div class="flex items-start gap-3 p-4">
                <div
                    class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600 font-bold">
                    !
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-800">Hay errores en el formulario</p>
                    <ul class="text-sm text-slate-600 mt-1 list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" onclick="cerrarToast(this)"
                    class="text-slate-400 hover:text-slate-700 text-lg leading-none">
                    ×
                </button>
            </div>
            <div class="h-1 bg-amber-500 toast-bar"></div>
        </div>
    @endif
</div>



