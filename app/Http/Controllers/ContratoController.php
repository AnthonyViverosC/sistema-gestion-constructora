<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Documento;
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

        $ultimosContratos = Contrato::with('createdBy')->latest()->take(5)->get();
        $ultimosDocumentos = Documento::with('contrato')->latest()->take(5)->get();
        $ultimasTareas = Tarea::with(['contrato', 'assignedTo'])
            ->when(! $puedeVerTodasTareas, fn ($query) => $query->where('assigned_to', auth()->id()))
            ->where('estado', '!=', 'Completada')
            ->orderBy('fecha_limite')
            ->take(5)
            ->get();

        return view('dashboard', compact(
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
            'tareasPendientes',
            'tareasCompletadas',
            'tareasVencidas',
            'tareasPorVencer',
            'contratosVencidos',
            'contratosPorVencer',
            'contratosVigentes',
            'ultimosContratos',
            'ultimosDocumentos',
            'ultimasTareas'
        ));
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['documentos.uploadedBy', 'createdBy', 'tareas.documento', 'tareas.assignedTo', 'tareas.createdBy']);
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

        return view('contratos.show', compact('contrato', 'estadoVigencia', 'usuarios', 'auditorias', 'puedeVerTodasTareas'));
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
        $contrato->load('documentos');

        if ($contrato->documentos->isEmpty()) {
            return back()->with('error', 'El contrato no tiene documentos cargados.');
        }

        $pendientes = $contrato->documentos
            ->filter(fn ($documento) => strtolower($documento->estado) !== 'aprobado')
            ->count();

        if ($pendientes > 0) {
            return back()->with('error', 'No se puede completar: hay documentos sin aprobar.');
        }

        $contrato->update([
            'estado' => 'Documentación completa',
            'etiqueta' => 'Completo',
        ]);

        Auditoria::registrar('completar', 'contratos', $contrato->id, 'Documentación completa validada.', $contrato->id);

        return back()->with('success', 'Contrato marcado como documentación completa.');
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
}

