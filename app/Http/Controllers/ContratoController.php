<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContratoRequest;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\DocumentoRequerido;
use App\Models\Auditoria;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $contratos = $this->queryContratos($request)->paginate(20);

        return view('contratos.index', [
            'contratos' => $contratos,
            'busqueda'  => $request->busqueda ?? '',
            'filtro'    => $request->filtro ?? '',
        ]);
    }

    public function buscar(Request $request)
    {
        $contratos = $this->queryContratos($request)->take(100)->get();

        return response()->json($contratos);
    }

    public function create()
    {
        return view('contratos.create');
    }

    public function store(ContratoRequest $request)
    {
        $datos              = $request->validated();
        $datos['created_by'] = auth()->id();

        $contrato = Contrato::create($datos);
        $this->asegurarPlantillaDocumental($contrato);

        Auditoria::registrar('crear', 'contratos', $contrato->id, 'Contrato creado: '.$contrato->numero_contrato, $contrato->id);

        return redirect()->route('contratos.index')->with('success', 'Contrato creado correctamente.');
    }

    public function dashboard()
    {
        $hoy              = Carbon::today();
        $puedeVerTodasTareas = auth()->user()->puedeGestionar();

        $kpis = Cache::remember('dashboard_kpis', 300, function () {
            return [
                'totalContratos'                   => Contrato::count(),
                'contratosActivos'                 => Contrato::where('estado', 'Activo')->count(),
                'contratosPendientes'              => Contrato::where('estado', 'Pendiente')->count(),
                'contratosFinalizados'             => Contrato::where('estado', 'Finalizado')->count(),
                'contratosCancelados'              => Contrato::where('estado', 'Cancelado')->count(),
                'contratosDocumentacionCompleta'   => Contrato::where('estado', 'Documentación completa')->count(),
                'contratosDocumentacionIncompleta' => Contrato::where('estado', '!=', 'Documentación completa')->count(),
                'totalDocumentos'                  => Documento::count(),
                'documentosPendientes'             => Documento::where('estado', 'Pendiente')->count(),
                'documentosAprobados'              => Documento::where('estado', 'Aprobado')->count(),
                'documentosObservados'             => Documento::where('estado', 'Observado')->count(),
                'documentosRechazados'             => Documento::where('estado', 'Rechazado')->count(),
                'contratosVencidos'                => Contrato::vencidos()->count(),
                'contratosPorVencer'               => Contrato::porVencer()->count(),
                'contratosVigentes'                => Contrato::vigentes()->count(),
            ];
        });

        $baseTareas = Tarea::query()
            ->when(! $puedeVerTodasTareas, fn ($q) => $q->where('assigned_to', auth()->id()));

        $tareasPendientes = (clone $baseTareas)->where('estado', 'Pendiente')->count();
        $tareasCompletadas = (clone $baseTareas)->where('estado', 'Completada')->count();
        $tareasVencidas   = (clone $baseTareas)->where('estado', '!=', 'Completada')
            ->whereDate('fecha_limite', '<', $hoy)->count();
        $tareasPorVencer  = (clone $baseTareas)->where('estado', '!=', 'Completada')
            ->whereBetween('fecha_limite', [$hoy, $hoy->copy()->addDays(2)])->count();

        $contratos = Contrato::with(['documentos', 'documentosRequeridos', 'createdBy'])->get();

        $resumenDocumentalContratos = $contratos->map(function ($contrato) {
            $resumen = $contrato->resumenDocumental();

            return [
                'contrato'    => $contrato,
                'porcentaje'  => $resumen['porcentaje'],
                'pendientes'  => $resumen['pendientes'],
            ];
        });

        $promedioAvanceDocumental = (int) round($resumenDocumentalContratos->avg('porcentaje') ?? 0);
        $contratosCriticos        = $resumenDocumentalContratos
            ->sortBy([['pendientes', 'desc'], ['porcentaje', 'asc']])
            ->take(5)
            ->values();

        $distribucionEstadosContrato = [
            ['label' => 'Activos',    'value' => $kpis['contratosActivos'],    'color' => 'bg-green-500'],
            ['label' => 'Pendientes', 'value' => $kpis['contratosPendientes'], 'color' => 'bg-amber-500'],
            ['label' => 'Finalizados','value' => $kpis['contratosFinalizados'],'color' => 'bg-slate-500'],
            ['label' => 'Cancelados', 'value' => $kpis['contratosCancelados'], 'color' => 'bg-red-500'],
        ];

        $distribucionEstadosDocumento = [
            ['label' => 'Aprobados',  'value' => $kpis['documentosAprobados'],  'color' => 'bg-green-500'],
            ['label' => 'Pendientes', 'value' => $kpis['documentosPendientes'], 'color' => 'bg-amber-500'],
            ['label' => 'Observados', 'value' => $kpis['documentosObservados'], 'color' => 'bg-orange-500'],
            ['label' => 'Rechazados', 'value' => $kpis['documentosRechazados'], 'color' => 'bg-red-500'],
        ];

        $rendimientoResponsables = User::withCount([
            'tareas as pendientes'       => fn ($q) => $q->where('estado', '!=', 'Completada'),
            'tareas as completadas'      => fn ($q) => $q->where('estado', 'Completada'),
            'documentosSubidos as documentos',
        ])
            ->orderBy('name')
            ->get()
            ->filter(fn ($u) => $u->pendientes > 0 || $u->completadas > 0 || $u->documentos > 0)
            ->map(fn ($u) => [
                'usuario'    => $u,
                'pendientes' => $u->pendientes,
                'completadas'=> $u->completadas,
                'documentos' => $u->documentos,
            ])
            ->sortByDesc('pendientes')
            ->take(8)
            ->values();

        $actividadReciente = Auditoria::with('user')->latest()->take(8)->get();

        $ultimasTareas = Tarea::with(['contrato', 'assignedTo'])
            ->when(! $puedeVerTodasTareas, fn ($q) => $q->where('assigned_to', auth()->id()))
            ->where('estado', '!=', 'Completada')
            ->orderBy('fecha_limite')
            ->take(3)
            ->get();

        return view('dashboard_executive', array_merge($kpis, compact(
            'tareasPendientes',
            'tareasCompletadas',
            'tareasVencidas',
            'tareasPorVencer',
            'ultimasTareas',
            'promedioAvanceDocumental',
            'contratosCriticos',
            'distribucionEstadosContrato',
            'distribucionEstadosDocumento',
            'rendimientoResponsables',
            'actividadReciente'
        )));
    }

    public function show(Contrato $contrato)
    {
        $this->asegurarPlantillaDocumental($contrato);
        $contrato->load([
            'documentos.uploadedBy',
            'documentos.versiones.uploadedBy',
            'documentos.observaciones',
            'documentosRequeridos',
            'createdBy',
            'tareas.documento',
            'tareas.assignedTo',
            'tareas.createdBy',
        ]);
        $puedeVerTodasTareas = auth()->user()->puedeGestionar();

        if (! $puedeVerTodasTareas) {
            $contrato->setRelation('tareas', $contrato->tareas->where('assigned_to', auth()->id())->values());
        }

        $usuarios   = User::orderBy('name')->get();
        $auditorias = Auditoria::with('user')
            ->where('contrato_id', $contrato->id)
            ->orWhere(fn ($q) => $q->where('modulo', 'contratos')->where('registro_id', $contrato->id))
            ->latest()
            ->take(20)
            ->get();

        $estadoVigencia        = $contrato->estado_vigencia;
        $resumenDocumental     = $contrato->resumenDocumental();
        $seccionesDocumentales = $this->agruparEstructuraDocumental($contrato);

        $documentos                  = $contrato->documentos->sortByDesc(fn ($d) => $d->fecha_carga ?? $d->created_at)->values();
        $tareasPendientes            = $contrato->tareas->where('estado', '!=', 'Completada')->sortBy('fecha_limite')->values();
        $proximaTarea                = $tareasPendientes->first();
        $documentosObservados        = $documentos->filter(fn ($d) => str_contains(strtolower((string) $d->estado), 'observ') || str_contains(strtolower((string) $d->estado), 'rechaz'))->count();
        $documentosPendientesRevision = $documentos->filter(fn ($d) => str_contains(strtolower((string) $d->estado), 'pend') || str_contains(strtolower((string) $d->estado), 'revisi'))->count();
        $documentosConVersiones      = $documentos->filter(fn ($d) => $d->versiones->count() > 1)->count();
        $documentosSinResponsable    = $tareasPendientes->whereNull('assigned_to')->count();
        $diasParaVencer              = $contrato->fecha_fin ? Carbon::today()->diffInDays($contrato->fecha_fin, false) : null;
        $riesgos                     = collect();

        if ($estadoVigencia === 'Vencido') {
            $riesgos->push(['nivel' => 'critico', 'titulo' => 'Contrato vencido', 'detalle' => 'La fecha final ya se cumplio y el expediente requiere cierre o renovacion.']);
        } elseif ($estadoVigencia === 'Por vencer') {
            $riesgos->push(['nivel' => 'alerta', 'titulo' => 'Contrato por vencer', 'detalle' => 'Quedan '.max(0, (int) $diasParaVencer).' dia(s) para revisar documentos y tareas pendientes.']);
        }

        if ($resumenDocumental['pendientes'] > 0) {
            $riesgos->push(['nivel' => 'alerta', 'titulo' => 'Expediente incompleto', 'detalle' => 'Faltan '.$resumenDocumental['pendientes'].' requisito(s) documentales obligatorios por aprobar.']);
        }

        if ($documentosObservados > 0) {
            $riesgos->push(['nivel' => 'critico', 'titulo' => 'Documentos observados', 'detalle' => 'Hay '.$documentosObservados.' documento(s) con observaciones o rechazo esperando correccion.']);
        }

        if ($tareasPendientes->isNotEmpty()) {
            $vencidas = $tareasPendientes->filter(fn ($t) => $t->fecha_limite?->isPast())->count();
            $riesgos->push([
                'nivel'  => $vencidas > 0 ? 'critico' : 'seguimiento',
                'titulo' => $vencidas > 0 ? 'Tareas vencidas' : 'Tareas abiertas',
                'detalle'=> $vencidas > 0 ? 'Hay '.$vencidas.' tarea(s) vencida(s) que pueden frenar el cierre del contrato.' : 'Hay '.$tareasPendientes->count().' tarea(s) abierta(s) que aun requieren seguimiento.',
            ]);
        }

        $resumenEjecutivo = [
            ['label' => 'Riesgos activos',        'value' => $riesgos->count(),                  'hint' => $riesgos->isEmpty() ? 'Sin alertas fuertes' : 'Prioridades para hoy'],
            ['label' => 'Pendientes documentales', 'value' => $resumenDocumental['pendientes'],    'hint' => $resumenDocumental['cumplidos'].' requisito(s) ya aprobados'],
            ['label' => 'Pendientes de revision',  'value' => $documentosPendientesRevision,       'hint' => $documentosObservados.' con observacion o rechazo'],
            ['label' => 'Documentos versionados',  'value' => $documentosConVersiones,             'hint' => 'Control de cambios visible'],
        ];

        return view('contratos.show', compact(
            'contrato', 'estadoVigencia', 'usuarios', 'auditorias', 'puedeVerTodasTareas',
            'resumenDocumental', 'seccionesDocumentales', 'documentos', 'tareasPendientes',
            'proximaTarea', 'documentosObservados', 'documentosPendientesRevision',
            'documentosConVersiones', 'documentosSinResponsable', 'diasParaVencer',
            'riesgos', 'resumenEjecutivo'
        ));
    }

    public function edit(Contrato $contrato)
    {
        return view('contratos.edit', compact('contrato'));
    }

    public function update(ContratoRequest $request, Contrato $contrato)
    {
        $contrato->update($request->validated());

        Auditoria::registrar('actualizar', 'contratos', $contrato->id, 'Contrato actualizado: '.$contrato->numero_contrato, $contrato->id);

        return redirect()->route('contratos.show', $contrato)->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contrato $contrato)
    {
        Auditoria::registrar('eliminar', 'contratos', $contrato->id, 'Contrato eliminado: '.$contrato->numero_contrato, $contrato->id);
        $contrato->delete();

        return redirect()->route('contratos.index')->with('success', 'Contrato eliminado correctamente.');
    }

    public function completarDocumentacion(Contrato $contrato)
    {
        $this->asegurarPlantillaDocumental($contrato);
        $contrato->load(['documentos', 'documentosRequeridos']);

        if ($contrato->documentos->isEmpty()) {
            return back()->with('error', 'El contrato no tiene documentos cargados.');
        }

        if ($contrato->resumenDocumental()['pendientes'] > 0) {
            return back()->with('error', 'No se puede completar: faltan documentos obligatorios aprobados.');
        }

        $contrato->update(['estado' => 'Documentación completa', 'etiqueta' => 'Completo']);
        Auditoria::registrar('completar', 'contratos', $contrato->id, 'Documentación completa validada.', $contrato->id);

        return back()->with('success', 'Contrato marcado como documentación completa.');
    }

    public function storeEstructuraDocumental(Request $request, Contrato $contrato)
    {
        $datos = $request->validate([
            'nombre'      => ['required', 'string', 'max:255'],
            'categoria'   => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'obligatorio' => ['nullable', 'boolean'],
        ], [
            'nombre.required'   => 'El nombre de la seccion es obligatorio.',
            'categoria.required'=> 'La categoria es obligatoria.',
        ]);

        $contrato->documentosRequeridos()->create([
            'nombre'      => $datos['nombre'],
            'categoria'   => $datos['categoria'],
            'descripcion' => $datos['descripcion'] ?? null,
            'obligatorio' => $request->boolean('obligatorio', true),
            'orden'       => ((int) $contrato->documentosRequeridos()->max('orden')) + 1,
        ]);

        Auditoria::registrar('crear', 'estructura_documental', $contrato->id, 'Seccion documental agregada: '.$datos['nombre'], $contrato->id);

        return back()->with('success', 'Seccion documental agregada correctamente.');
    }

    public function exportarDocumentosCsv()
    {
        $contratos    = Contrato::with(['documentos', 'documentosRequeridos'])->orderBy('numero_contrato')->get();
        $nombreArchivo = 'reporte-documental-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($contratos) {
            $salida = fopen('php://output', 'w');
            fwrite($salida, "\xEF\xBB\xBF");
            fputcsv($salida, ['Contrato', 'Contratista', 'Estado contrato', 'Requisito', 'Categoria', 'Documentos cargados', 'Estado requisito', 'Documento aprobado'], ';');

            foreach ($contratos as $contrato) {
                $resumen = $contrato->resumenDocumental();

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
        }, $nombreArchivo, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function queryContratos(Request $request)
    {
        $busqueda = $request->busqueda ?? '';
        $filtro   = $request->filtro ?? '';

        return Contrato::with('createdBy')
            ->when($busqueda, fn ($q) => $q->where(fn ($sub) => $sub
                ->where('numero_contrato', 'like', "%{$busqueda}%")
                ->orWhere('cedula_contratista', 'like', "%{$busqueda}%")
                ->orWhere('nombre_contratista', 'like', "%{$busqueda}%")
                ->orWhere('estado', 'like', "%{$busqueda}%")
                ->orWhereDate('fecha_contrato', $busqueda)
                ->orWhereDate('fecha_inicio', $busqueda)
                ->orWhereDate('fecha_fin', $busqueda)
            ))
            ->when($filtro === 'Vencido',    fn ($q) => $q->vencidos())
            ->when($filtro === 'Por vencer', fn ($q) => $q->porVencer())
            ->when($filtro === 'Vigente',    fn ($q) => $q->vigentes())
            ->orderBy('id', 'desc');
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

    private function agruparEstructuraDocumental(Contrato $contrato)
    {
        return $contrato->documentosRequeridos
            ->groupBy('categoria')
            ->map(function ($items, $categoria) use ($contrato) {
                return [
                    'categoria' => $categoria,
                    'items'     => $items->values()->map(function (DocumentoRequerido $requisito) use ($contrato) {
                        $documentosCategoria = $contrato->documentos->where('categoria', $requisito->categoria);
                        $documentoAprobado   = $documentosCategoria->first(fn ($d) => strtolower($d->estado) === 'aprobado');

                        return [
                            'requisito'          => $requisito,
                            'documentos_cargados'=> $documentosCategoria->count(),
                            'cumplido'           => (bool) $documentoAprobado,
                        ];
                    }),
                ];
            })
            ->values();
    }
}
