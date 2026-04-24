<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
use App\Models\Auditoria;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\DocumentoVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function create(Request $request, Contrato $contrato)
    {
        $categoria = $request->string('categoria')->toString();
        $etiqueta = $request->string('etiqueta')->toString();
        $fechaDesde = $request->string('fecha_desde')->toString();
        $fechaHasta = $request->string('fecha_hasta')->toString();

        $documentos = $contrato->documentos()
            ->with('uploadedBy')
            ->withCount('versiones')
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->when($etiqueta !== '', fn ($q) => $q->where('etiqueta', $etiqueta))
            ->when($fechaDesde !== '', fn ($q) => $q->whereDate('fecha_carga', '>=', $fechaDesde))
            ->when($fechaHasta !== '', fn ($q) => $q->whereDate('fecha_carga', '<=', $fechaHasta))
            ->latest()
            ->get();

        $seccionesDocumentales = $contrato->documentosRequeridos()
            ->orderBy('orden')
            ->get()
            ->groupBy('categoria')
            ->map(function ($items, $categoria) use ($contrato) {
                $documentosCategoria = $contrato->documentos->where('categoria', $categoria);
                $cumplidos = $documentosCategoria->filter(fn ($d) => strtolower($d->estado) === 'aprobado')->count();

                return [
                    'categoria' => $categoria,
                    'total_requisitos' => $items->count(),
                    'documentos_cargados' => $documentosCategoria->count(),
                    'cumplidos' => min($cumplidos, $items->count()),
                ];
            })
            ->values();

        $categoriasDisponibles = $contrato->documentosRequeridos()
            ->orderBy('categoria')->pluck('categoria')
            ->merge($contrato->documentos()->pluck('categoria'))
            ->filter()->unique()->values();

        $etiquetasDisponibles = $contrato->documentos()->pluck('etiqueta')->filter()->unique()->values();

        return view('documentos.create', compact(
            'contrato', 'documentos', 'categoria', 'etiqueta',
            'fechaDesde', 'fechaHasta', 'categoriasDisponibles',
            'etiquetasDisponibles', 'seccionesDocumentales'
        ));
    }

    public function store(StoreDocumentoRequest $request, Contrato $contrato)
    {
        $archivo = $request->file('archivo');
        $nombreOriginal = basename($archivo->getClientOriginalName());
        $rutaArchivo = $archivo->store('documentos', 'local');

        $documento = Documento::create([
            'contrato_id' => $contrato->id,
            'uploaded_by' => auth()->id(),
            'nombre_documento' => $request->nombre_documento,
            'nombre_original' => $nombreOriginal,
            'archivo' => $rutaArchivo,
            'categoria' => $request->categoria,
            'fecha_carga' => $request->fecha_carga ?? now()->toDateString(),
            'estado' => $request->estado,
            'etiqueta' => $request->etiqueta,
            'descripcion' => $request->descripcion,
        ]);

        $documento->versiones()->create([
            'uploaded_by' => auth()->id(),
            'numero_version' => 1,
            'archivo' => $rutaArchivo,
            'nombre_original' => $nombreOriginal,
            'extension' => $archivo->extension(),
            'tamano' => $archivo->getSize(),
            'observacion' => 'Versión inicial del documento.',
        ]);

        Auditoria::registrar('crear', 'documentos', $documento->id, 'Documento cargado: '.$nombreOriginal, $contrato->id);

        return redirect()->route('documentos.create', $contrato)->with('success', 'Documento guardado correctamente.');
    }

    public function edit(Documento $documento)
    {
        $this->authorize('update', $documento);

        $documento->load(['uploadedBy', 'versiones.uploadedBy', 'observaciones.user']);

        return view('documentos.edit', compact('documento'));
    }

    public function update(UpdateDocumentoRequest $request, Documento $documento)
    {
        $this->authorize('update', $documento);

        $datosActualizar = [
            'nombre_documento' => $request->nombre_documento,
            'categoria' => $request->categoria,
            'fecha_carga' => $request->fecha_carga,
            'estado' => $request->estado,
            'etiqueta' => $request->etiqueta,
            'descripcion' => $request->descripcion,
        ];

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');

            $datosActualizar['nombre_original'] = basename($archivo->getClientOriginalName());
            $datosActualizar['archivo'] = $archivo->store('documentos', 'local');
            $datosActualizar['uploaded_by'] = auth()->id();

            $documento->versiones()->create([
                'uploaded_by' => auth()->id(),
                'numero_version' => ((int) $documento->versiones()->max('numero_version')) + 1,
                'archivo' => $datosActualizar['archivo'],
                'nombre_original' => $datosActualizar['nombre_original'],
                'extension' => $archivo->extension(),
                'tamano' => $archivo->getSize(),
                'observacion' => 'Archivo reemplazado desde edición del documento.',
            ]);
        }

        $documento->update($datosActualizar);

        Auditoria::registrar('actualizar', 'documentos', $documento->id, 'Documento actualizado: '.$documento->nombre_documento, $documento->contrato_id);

        return redirect()->route('documentos.create', $documento->contrato)->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento);

        $rutas = $documento->versiones()->pluck('archivo')
            ->push($documento->archivo)
            ->filter()
            ->unique();

        foreach ($rutas as $ruta) {
            if (Storage::disk('local')->exists($ruta)) {
                Storage::disk('local')->delete($ruta);
            }
        }

        $contrato = $documento->contrato;

        Auditoria::registrar('eliminar', 'documentos', $documento->id, 'Documento eliminado: '.$documento->nombre_documento, $contrato->id);

        $documento->delete();

        return redirect()->route('documentos.create', $contrato)->with('success', 'Documento eliminado correctamente.');
    }

    public function download(Documento $documento)
    {
        $this->authorize('view', $documento);

        if (! $documento->archivo || ! Storage::disk('local')->exists($documento->archivo)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }

        $extension = pathinfo($documento->archivo, PATHINFO_EXTENSION);
        $nombreArchivo = $documento->nombre_original ?? $documento->nombre_documento;

        if (! str_ends_with(strtolower($nombreArchivo), '.'.strtolower($extension))) {
            $nombreArchivo .= '.'.$extension;
        }

        return Storage::disk('local')->download($documento->archivo, $nombreArchivo);
    }

    public function downloadVersion(DocumentoVersion $version)
    {
        $this->authorize('view', $version->documento);

        if (! $version->archivo || ! Storage::disk('local')->exists($version->archivo)) {
            return redirect()->back()->with('error', 'El archivo de esta versión no existe.');
        }

        $extension = pathinfo($version->archivo, PATHINFO_EXTENSION);
        $nombreArchivo = $version->nombre_original ?? optional($version->documento)->nombre_documento ?? 'documento';

        if ($extension && ! str_ends_with(strtolower($nombreArchivo), '.'.strtolower($extension))) {
            $nombreArchivo .= '.'.$extension;
        }

        return Storage::disk('local')->download($version->archivo, 'v'.$version->numero_version.'-'.$nombreArchivo);
    }

    public function storeObservacion(Request $request, Documento $documento)
    {
        $this->authorize('update', $documento);

        $request->validate([
            'observacion' => 'required|string|max:1000',
        ]);

        $documento->observaciones()->create([
            'user_id' => auth()->id(),
            'observacion' => $request->observacion,
        ]);

        Auditoria::registrar('observacion', 'documentos', $documento->id, 'Observación agregada al documento: '.$documento->nombre_documento, $documento->contrato_id);

        return redirect()->route('documentos.edit', $documento)->with('success', 'Observación agregada correctamente.');
    }

    public function view(Documento $documento)
    {
        $this->authorize('view', $documento);

        if (! $documento->archivo || ! Storage::disk('local')->exists($documento->archivo)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }

        $extension = strtolower(pathinfo($documento->archivo, PATHINFO_EXTENSION));
        $nombreArchivo = $documento->nombre_original ?? $documento->nombre_documento;

        if (! in_array($extension, ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp'], true)) {
            return Storage::disk('local')->download($documento->archivo, $nombreArchivo);
        }

        return Storage::disk('local')->response($documento->archivo, $nombreArchivo, [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
