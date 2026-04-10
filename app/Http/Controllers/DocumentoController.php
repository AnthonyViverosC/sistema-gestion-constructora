<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Documento;
use App\Models\DocumentoVersion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function create(Contrato $contrato)
    {
        $documentos = $contrato->documentos()->with('uploadedBy')->withCount('versiones')->latest()->get();

        return view('documentos.create', compact('contrato', 'documentos'));
    }

    public function store(Request $request, Contrato $contrato)
    {
        $request->validate([
            'nombre_documento' => 'required|string|max:255',
            'archivo' => 'required|file|max:20480',
            'categoria' => 'required|string|max:100',
            'fecha_carga' => 'nullable|date',
            'estado' => 'required|string|max:50',
            'etiqueta' => 'nullable|in:Pendiente,Falta firma,Falta revisar,Completo',
            'descripcion' => 'nullable|string',
        ]);

        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $rutaArchivo = $archivo->store('documentos', 'public');

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
            'extension' => $archivo->getClientOriginalExtension(),
            'tamano' => $archivo->getSize(),
            'observacion' => 'Versión inicial del documento.',
        ]);

        Auditoria::registrar('crear', 'documentos', $documento->id, 'Documento cargado: '.$nombreOriginal, $contrato->id);

        return redirect()
            ->route('documentos.create', $contrato)
            ->with('success', 'Documento guardado correctamente.');
    }

    public function edit(Documento $documento)
    {
        $documento->load(['uploadedBy', 'versiones.uploadedBy']);

        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'nombre_documento' => 'required|string|max:255',
            'archivo' => 'nullable|file|max:20480',
            'categoria' => 'required|string|max:100',
            'fecha_carga' => 'nullable|date',
            'estado' => 'required|string|max:50',
            'etiqueta' => 'nullable|in:Pendiente,Falta firma,Falta revisar,Completo',
            'descripcion' => 'nullable|string',
        ]);

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
            $datosActualizar['nombre_original'] = $archivo->getClientOriginalName();
            $datosActualizar['archivo'] = $archivo->store('documentos', 'public');
            $datosActualizar['uploaded_by'] = auth()->id();

            $documento->versiones()->create([
                'uploaded_by' => auth()->id(),
                'numero_version' => ((int) $documento->versiones()->max('numero_version')) + 1,
                'archivo' => $datosActualizar['archivo'],
                'nombre_original' => $datosActualizar['nombre_original'],
                'extension' => $archivo->getClientOriginalExtension(),
                'tamano' => $archivo->getSize(),
                'observacion' => 'Archivo reemplazado desde edición del documento.',
            ]);
        }

        $documento->update($datosActualizar);

        Auditoria::registrar('actualizar', 'documentos', $documento->id, 'Documento actualizado: '.$documento->nombre_documento, $documento->contrato_id);

        return redirect()
            ->route('documentos.create', $documento->contrato)
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Documento $documento)
    {
        $rutas = $documento->versiones()
            ->pluck('archivo')
            ->push($documento->archivo)
            ->filter()
            ->unique();

        foreach ($rutas as $ruta) {
            if (Storage::disk('public')->exists($ruta)) {
                Storage::disk('public')->delete($ruta);
            }
        }

        $contrato = $documento->contrato;
        Auditoria::registrar('eliminar', 'documentos', $documento->id, 'Documento eliminado: '.$documento->nombre_documento, $contrato->id);

        $documento->delete();

        return redirect()
            ->route('documentos.create', $contrato)
            ->with('success', 'Documento eliminado correctamente.');
    }

    public function download(Documento $documento)
    {
        if (! $documento->archivo || ! Storage::disk('public')->exists($documento->archivo)) {
            return redirect()
                ->back()
                ->with('error', 'El archivo no existe.');
        }

        $extension = pathinfo($documento->archivo, PATHINFO_EXTENSION);
        $nombreArchivo = $documento->nombre_original ?? $documento->nombre_documento;

        if (! str_ends_with(strtolower($nombreArchivo), '.'.strtolower($extension))) {
            $nombreArchivo .= '.'.$extension;
        }

        return Storage::disk('public')->download($documento->archivo, $nombreArchivo);
    }

    public function downloadVersion(DocumentoVersion $version)
    {
        if (! $version->archivo || ! Storage::disk('public')->exists($version->archivo)) {
            return redirect()
                ->back()
                ->with('error', 'El archivo de esta versión no existe.');
        }

        $extension = pathinfo($version->archivo, PATHINFO_EXTENSION);
        $nombreArchivo = $version->nombre_original ?? optional($version->documento)->nombre_documento ?? 'documento';

        if ($extension && ! str_ends_with(strtolower($nombreArchivo), '.'.strtolower($extension))) {
            $nombreArchivo .= '.'.$extension;
        }

        return Storage::disk('public')->download($version->archivo, 'v'.$version->numero_version.'-'.$nombreArchivo);
    }

    public function view(Documento $documento)
    {
        if (! $documento->archivo || ! Storage::disk('public')->exists($documento->archivo)) {
            return redirect()
                ->back()
                ->with('error', 'El archivo no existe.');
        }

        return response()->file(storage_path('app/public/'.$documento->archivo));
    }
}
