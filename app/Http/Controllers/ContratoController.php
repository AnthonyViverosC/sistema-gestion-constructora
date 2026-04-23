<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Documento;
use App\Models\DocumentoRequerido;
use App\Models\Auditoria;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->busqueda;
        $filtro = $request->filtro;

        $contratos = Contrato::with('createdBy')
            ->when($busqueda, function ($query) use ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('numero_contrato', 'like', '%'.$busqueda.'%')
                    ->orWhere('cedula_contratista', 'like', '%'.$busqueda.'%')
                    ->orWhere('nombre_contratista', 'like', '%'.$busqueda.'%')
                    ->orWhere('estado', 'like', '%'.$busqueda.'%')
                    ->orWhereDate('fecha_contrato', $busqueda)
                    ->orWhereDate('fecha_inicio', $busqueda)
                    ->orWhereDate('fecha_fin', $busqueda);
            });
        })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($contrato) {
                $contrato->estado_vigencia = $this->calcularEstadoVigencia($contrato->fecha_fin);

                return $contrato;
            });

        if ($filtro) {
            $contratos = $contratos->filter(function ($contrato) use ($filtro) {
                return strtolower($contrato->estado_vigencia) === strtolower($filtro);
            })->values();
        }

        return view('contratos.index', compact('contratos', 'busqueda', 'filtro'));
    }

    public function buscar(Request $request)
    {
        $busqueda = $request->busqueda;
        $filtro = $request->filtro;

        $contratos = Contrato::when($busqueda, function ($query) use ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('numero_contrato', 'like', '%'.$busqueda.'%')
                    ->orWhere('cedula_contratista', 'like', '%'.$busqueda.'%')
                    ->orWhere('nombre_contratista', 'like', '%'.$busqueda.'%')
                    ->orWhere('estado', 'like', '%'.$busqueda.'%')
                    ->orWhereDate('fecha_contrato', $busqueda)
                    ->orWhereDate('fecha_inicio', $busqueda)
                    ->orWhereDate('fecha_fin', $busqueda);
            });
        })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($contrato) {
                $contrato->estado_vigencia = $this->calcularEstadoVigencia($contrato->fecha_fin);

                return $contrato;
            });

        if ($filtro) {
            $contratos = $contratos->filter(function ($contrato) use ($filtro) {
                return strtolower($contrato->estado_vigencia) === strtolower($filtro);
            })->values();
        }

        return response()->json($contratos);
    }

    public function create()
    {
        return view('contratos.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'numero_contrato' => 'required|unique:contratos,numero_contrato',
            'fecha_contrato' => 'required|date',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'cedula_contratista' => 'required',
            'nombre_contratista' => 'required',
            'estado' => 'required|in:Activo,Pendiente,Finalizado,Cancelado,Documentación completa',
            'etiqueta' => 'nullable|in:Pendiente,Falta firma,Falta revisar,Completo',
            'descripcion' => 'nullable',
        ], [
            'numero_contrato.required' => 'El número de contrato es obligatorio.',
            'numero_contrato.unique' => 'Este número de contrato ya existe.',
            'fecha_contrato.required' => 'La fecha del contrato es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_fin.date' => 'La fecha fin no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
            'cedula_contratista.required' => 'La cédula del contratista es obligatoria.',
            'nombre_contratista.required' => 'El nombre del contratista es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $datos['created_by'] = auth()->id();

        $contrato = Contrato::create($datos);
        $this->asegurarPlantillaDocumental($contrato);

        Auditoria::registrar('crear', 'contratos', $contrato->id, 'Contrato creado: '.$contrato->numero_contrato, $contrato->id);

        return redirect()
            ->route('contratos.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function dashboard()
    {
        $hoy = Carbon::today();
        $limite = Carbon::today()->addDays(15);
        $puedeVerTodasTareas = in_array(auth()->user()->rol, ['admin', 'gestor']);

        $totalContratos = Contrato::count();
        $contratosActivos = Contrato::where('estado', 'Activo')->count();
        $contratosPendientes = Contrato::where('estado', 'Pendiente')->count();
        $contratosFinalizados = Contrato::where('estado', 'Finalizado')->count();
        $contratosCancelados = Contrato::where('estado', 'Cancelado')->count();
        $contratosDocumentacionCompleta = Contrato::where('estado', 'Documentación completa')->count();
        $contratosDocumentacionIncompleta = Contrato::where('estado', '!=', 'Documentación completa')->count();
        $totalDocumentos = Documento::count();
        $documentosPendientes = Documento::where('estado', 'Pendiente')->count();
        $documentosAprobados = Documento::where('estado', 'Aprobado')->count();
        $documentosObservados = Documento::where('estado', 'Observado')->count();
        $documentosRechazados = Documento::where('estado', 'Rechazado')->count();
        $baseTareas = Tarea::query()
            ->when(! $puedeVerTodasTareas, fn ($query) => $query->where('assigned_to', auth()->id()));

        $tareasPendientes = (clone $baseTareas)->where('estado', 'Pendiente')->count();
        $tareasCompletadas = (clone $baseTareas)->where('estado', 'Completada')->count();
        $tareasVencidas = (clone $baseTareas)->where('estado', '!=', 'Completada')
            ->whereDate('fecha_limite', '<', $hoy)
            ->count();
        $tareasPorVencer = (clone $baseTareas)->where('estado', '!=', 'Completada')
            ->whereBetween('fecha_limite', [$hoy, $hoy->copy()->addDays(2)])
            ->count();

        $contratosVencidos = Contrato::whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<', $hoy)
            ->count();

        $contratosPorVencer = Contrato::whereNotNull('fecha_fin')
            ->whereBetween('fecha_fin', [$hoy, $limite])
            ->count();

        $contratosVigentes = Contrato::whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '>', $limite)
            ->count();

        $contratos = Contrato::with(['documentos', 'documentosRequeridos', 'createdBy'])->get();

        $resumenDocumentalContratos = $contratos->map(function ($contrato) {
            $this->asegurarPlantillaDocumental($contrato);
            $contrato->loadMissing(['documentos', 'documentosRequeridos']);
            $resumen = $this->calcularResumenDocumental($contrato);

            return [
                'contrato' => $contrato,
                'porcentaje' => $resumen['porcentaje'],
                'pendientes' => $resumen['pendientes'],
            ];
        });

        $promedioAvanceDocumental = (int) round($resumenDocumentalContratos->avg('porcentaje') ?? 0);
        $contratosCriticos = $resumenDocumentalContratos
            ->sortBy([
                ['pendientes', 'desc'],
                ['porcentaje', 'asc'],
            ])
            ->take(5)
            ->values();

        $distribucionEstadosContrato = [
            ['label' => 'Activos', 'value' => $contratosActivos, 'color' => 'bg-green-500'],
            ['label' => 'Pendientes', 'value' => $contratosPendientes, 'color' => 'bg-amber-500'],
            ['label' => 'Finalizados', 'value' => $contratosFinalizados, 'color' => 'bg-slate-500'],
            ['label' => 'Cancelados', 'value' => $contratosCancelados, 'color' => 'bg-red-500'],
        ];

        $distribucionEstadosDocumento = [
            ['label' => 'Aprobados', 'value' => $documentosAprobados, 'color' => 'bg-green-500'],
            ['label' => 'Pendientes', 'value' => $documentosPendientes, 'color' => 'bg-amber-500'],
            ['label' => 'Observados', 'value' => $documentosObservados, 'color' => 'bg-orange-500'],
            ['label' => 'Rechazados', 'value' => $documentosRechazados, 'color' => 'bg-red-500'],
        ];

        $rendimientoResponsables = User::orderBy('name')
            ->get()
            ->map(function ($usuario) {
                $pendientes = Tarea::where('assigned_to', $usuario->id)
                    ->where('estado', '!=', 'Completada')
                    ->count();
                $completadas = Tarea::where('assigned_to', $usuario->id)
                    ->where('estado', 'Completada')
                    ->count();
                $documentos = Documento::where('uploaded_by', $usuario->id)->count();

                return [
                    'usuario' => $usuario,
                    'pendientes' => $pendientes,
                    'completadas' => $completadas,
                    'documentos' => $documentos,
                ];
            })
            ->filter(fn ($item) => $item['pendientes'] > 0 || $item['completadas'] > 0 || $item['documentos'] > 0)
            ->sortByDesc('pendientes')
            ->take(8)
            ->values();

        $actividadReciente = Auditoria::with('user')
            ->latest()
            ->take(8)
            ->get();

        $ultimasTareas = Tarea::with(['contrato', 'assignedTo'])
            ->when(! $puedeVerTodasTareas, fn ($query) => $query->where('assigned_to', auth()->id()))
            ->where('estado', '!=', 'Completada')
            ->orderBy('fecha_limite')
            ->take(3)
            ->get();

        return view('dashboard_executive', compact(
            'totalContratos',
            'contratosActivos',
            'contratosPendientes',
            'contratosFinalizados',
            'contratosCancelados',
            'contratosDocumentacionCompleta',
            'contratosDocumentacionIncompleta',
            'totalDocumentos',
            'documentosPendientes',
            'documentosAprobados',
            'documentosObservados',
            'documentosRechazados',
            'tareasPendientes',
            'tareasCompletadas',
            'tareasVencidas',
            'tareasPorVencer',
            'contratosVencidos',
            'contratosPorVencer',
            'contratosVigentes',
            'ultimasTareas',
            'promedioAvanceDocumental',
            'contratosCriticos',
            'distribucionEstadosContrato',
            'distribucionEstadosDocumento',
            'rendimientoResponsables',
            'actividadReciente'
        ));
    }

    public function show(Contrato $contrato)
    {
        $this->asegurarPlantillaDocumental($contrato);
        $contrato->load(['documentos.uploadedBy', 'documentosRequeridos', 'createdBy', 'tareas.documento', 'tareas.assignedTo', 'tareas.createdBy']);
        $puedeVerTodasTareas = in_array(auth()->user()->rol, ['admin', 'gestor']);

        if (! $puedeVerTodasTareas) {
            $contrato->setRelation(
                'tareas',
                $contrato->tareas->where('assigned_to', auth()->id())->values()
            );
        }

        $usuarios = User::orderBy('name')->get();
        $auditorias = Auditoria::with('user')
            ->where('contrato_id', $contrato->id)
            ->orWhere(function ($query) use ($contrato) {
                $query->where('modulo', 'contratos')
                    ->where('registro_id', $contrato->id);
            })
            ->latest()
            ->take(20)
            ->get();
        $estadoVigencia = $this->calcularEstadoVigencia($contrato->fecha_fin);
        $resumenDocumental = $this->calcularResumenDocumental($contrato);
        $seccionesDocumentales = $this->agruparEstructuraDocumental($contrato);

        return view('contratos.show', compact('contrato', 'estadoVigencia', 'usuarios', 'auditorias', 'puedeVerTodasTareas', 'resumenDocumental', 'seccionesDocumentales'));
    }

    public function edit(Contrato $contrato)
    {
        return view('contratos.edit', compact('contrato'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $datos = $request->validate([
            'numero_contrato' => 'required|unique:contratos,numero_contrato,'.$contrato->id,
            'fecha_contrato' => 'required|date',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'cedula_contratista' => 'required',
            'nombre_contratista' => 'required',
            'estado' => 'required|in:Activo,Pendiente,Finalizado,Cancelado,Documentación completa',
            'etiqueta' => 'nullable|in:Pendiente,Falta firma,Falta revisar,Completo',
            'descripcion' => 'nullable',
        ], [
            'numero_contrato.required' => 'El número de contrato es obligatorio.',
            'numero_contrato.unique' => 'Este número de contrato ya existe.',
            'fecha_contrato.required' => 'La fecha del contrato es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_fin.date' => 'La fecha fin no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
            'cedula_contratista.required' => 'La cédula del contratista es obligatoria.',
            'nombre_contratista.required' => 'El nombre del contratista es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $contrato->update($datos);

        Auditoria::registrar('actualizar', 'contratos', $contrato->id, 'Contrato actualizado: '.$contrato->numero_contrato, $contrato->id);

        return redirect()
            ->route('contratos.show', $contrato)
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contrato $contrato)
    {
        Auditoria::registrar('eliminar', 'contratos', $contrato->id, 'Contrato eliminado: '.$contrato->numero_contrato, $contrato->id);

        $contrato->delete();

        return redirect()
            ->route('contratos.index')
            ->with('success', 'Contrato eliminado correctamente.');
    }

    public function completarDocumentacion(Contrato $contrato)
    {
        $this->asegurarPlantillaDocumental($contrato);
        $contrato->load(['documentos', 'documentosRequeridos']);

        if ($contrato->documentos->isEmpty()) {
            return back()->with('error', 'El contrato no tiene documentos cargados.');
        }

        $resumenDocumental = $this->calcularResumenDocumental($contrato);
        $pendientes = $resumenDocumental['pendientes'];

        if ($pendientes > 0) {
            return back()->with('error', 'No se puede completar: faltan documentos obligatorios aprobados.');
        }

        $contrato->update([
            'estado' => 'Documentación completa',
            'etiqueta' => 'Completo',
        ]);

        Auditoria::registrar('completar', 'contratos', $contrato->id, 'Documentación completa validada.', $contrato->id);

        return back()->with('success', 'Contrato marcado como documentación completa.');
    }

    public function storeEstructuraDocumental(Request $request, Contrato $contrato)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'obligatorio' => ['nullable', 'boolean'],
        ], [
            'nombre.required' => 'El nombre de la seccion es obligatorio.',
            'categoria.required' => 'La categoria es obligatoria.',
        ]);

        $contrato->documentosRequeridos()->create([
            'nombre' => $datos['nombre'],
            'categoria' => $datos['categoria'],
            'descripcion' => $datos['descripcion'] ?? null,
            'obligatorio' => $request->boolean('obligatorio', true),
            'orden' => ((int) $contrato->documentosRequeridos()->max('orden')) + 1,
        ]);

        Auditoria::registrar('crear', 'estructura_documental', $contrato->id, 'Seccion documental agregada: '.$datos['nombre'], $contrato->id);

        return back()->with('success', 'Seccion documental agregada correctamente.');
    }

    public function exportarDocumentosCsv()
    {
        $contratos = Contrato::with(['documentos', 'documentosRequeridos'])
            ->orderBy('numero_contrato')
            ->get();

        $nombreArchivo = 'reporte-documental-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($contratos) {
            $salida = fopen('php://output', 'w');
            fwrite($salida, "\xEF\xBB\xBF");
            fputcsv($salida, [
                'Contrato',
                'Contratista',
                'Estado contrato',
                'Requisito',
                'Categoria',
                'Documentos cargados',
                'Estado requisito',
                'Documento aprobado',
            ], ';');

            foreach ($contratos as $contrato) {
                $this->asegurarPlantillaDocumental($contrato);
                $contrato->load(['documentos', 'documentosRequeridos']);
                $resumen = $this->calcularResumenDocumental($contrato);

                foreach ($resumen['items'] as $item) {
                    fputcsv($salida, [
                        $contrato->numero_contrato,
                        $contrato->nombre_contratista,
                        $contrato->estado,
                        $item['requisito']->nombre,
                        $item['requisito']->categoria,
                        $item['documentos_cargados'],
                        $item['cumplido'] ? 'Aprobado' : 'Pendiente',
                        $item['documento_aprobado']?->nombre_original ?? $item['documento_aprobado']?->nombre_documento ?? '',
                    ], ';');
                }
            }

            fclose($salida);
        }, $nombreArchivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function calcularEstadoVigencia($fechaFin)
    {
        if (! $fechaFin) {
            return 'Sin definir';
        }

        $hoy = Carbon::today();
        $fechaFin = Carbon::parse($fechaFin);

        if ($fechaFin->lt($hoy)) {
            return 'Vencido';
        }

        if ($fechaFin->between($hoy, $hoy->copy()->addDays(15))) {
            return 'Por vencer';
        }

        return 'Vigente';
    }

    private function asegurarPlantillaDocumental(Contrato $contrato): void
    {
        if ($contrato->documentosRequeridos()->exists()) {
            return;
        }

        foreach (DocumentoRequerido::plantillaBase() as $item) {
            $contrato->documentosRequeridos()->create($item + ['obligatorio' => true]);
        }
    }

    private function calcularResumenDocumental(Contrato $contrato): array
    {
        $requisitos = $contrato->documentosRequeridos;
        $documentos = $contrato->documentos;

        $items = $requisitos->map(function (DocumentoRequerido $requisito) use ($documentos) {
            $documentosCategoria = $documentos->where('categoria', $requisito->categoria);
            $documentoAprobado = $documentosCategoria->first(fn ($documento) => strtolower($documento->estado) === 'aprobado');

            return [
                'requisito' => $requisito,
                'documentos_cargados' => $documentosCategoria->count(),
                'cumplido' => (bool) $documentoAprobado,
                'documento_aprobado' => $documentoAprobado,
            ];
        });

        $total = $items->count();
        $cumplidos = $items->where('cumplido', true)->count();
        $pendientes = $total - $cumplidos;
        $porcentaje = $total > 0 ? (int) round(($cumplidos / $total) * 100) : 0;

        return compact('items', 'total', 'cumplidos', 'pendientes', 'porcentaje');
    }

    private function agruparEstructuraDocumental(Contrato $contrato)
    {
        return $contrato->documentosRequeridos
            ->groupBy('categoria')
            ->map(function ($items, $categoria) use ($contrato) {
                return [
                    'categoria' => $categoria,
                    'items' => $items->values()->map(function (DocumentoRequerido $requisito) use ($contrato) {
                        $documentosCategoria = $contrato->documentos->where('categoria', $requisito->categoria);
                        $documentoAprobado = $documentosCategoria->first(fn ($documento) => strtolower($documento->estado) === 'aprobado');

                        return [
                            'requisito' => $requisito,
                            'documentos_cargados' => $documentosCategoria->count(),
                            'cumplido' => (bool) $documentoAprobado,
                        ];
                    }),
                ];
            })
            ->values();
    }
}

