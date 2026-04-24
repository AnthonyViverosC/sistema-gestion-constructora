<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [ContratoController::class, 'dashboard'])->name('dashboard');
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::patch('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/contratos/buscar', [ContratoController::class, 'buscar'])->name('contratos.buscar');

    Route::middleware('rol:admin,gestor,consulta')->group(function () {
        Route::resource('contratos', ContratoController::class)->only(['index']);
        Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
        Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
        Route::patch('/tareas/{tarea}/completar', [TareaController::class, 'complete'])->name('tareas.complete');
    });

    Route::middleware('rol:admin,gestor')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
        Route::get('/reportes/documentos.csv', [ContratoController::class, 'exportarDocumentosCsv'])->name('reportes.documentos.csv');

        Route::resource('contratos', ContratoController::class)->only(['create', 'store', 'edit', 'update']);
        Route::post('/contratos/{contrato}/estructura-documental', [ContratoController::class, 'storeEstructuraDocumental'])->name('contratos.estructura-documental.store');
        Route::post('/contratos/{contrato}/completar-documentacion', [ContratoController::class, 'completarDocumentacion'])->name('contratos.completar-documentacion');
        Route::post('/contratos/{contrato}/tareas', [TareaController::class, 'store'])->name('tareas.store');
        Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
    });

    Route::middleware('rol:admin,gestor,consulta')->group(function () {
        Route::resource('contratos', ContratoController::class)->only(['show']);
    });

    Route::middleware('rol:admin')->group(function () {
        Route::delete('/contratos/{contrato}', [ContratoController::class, 'destroy'])->name('contratos.destroy');
    });

    Route::middleware('rol:admin,gestor,consulta')->group(function () {
        Route::get('/contratos/{contrato}/documentos/create', [DocumentoController::class, 'create'])->name('documentos.create');
        Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])->name('documentos.download');
        Route::get('/documentos/versiones/{version}/download', [DocumentoController::class, 'downloadVersion'])->name('documentos.versiones.download');
        Route::get('/documentos/{documento}/view', [DocumentoController::class, 'view'])->name('documentos.view');
    });

    Route::middleware('rol:admin,gestor')->group(function () {
        Route::post('/contratos/{contrato}/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
        Route::get('/documentos/{documento}/edit', [DocumentoController::class, 'edit'])->name('documentos.edit');
        Route::put('/documentos/{documento}', [DocumentoController::class, 'update'])->name('documentos.update');
        Route::post('/documentos/{documento}/observaciones', [DocumentoController::class, 'storeObservacion'])->name('documentos.observaciones.store');
        Route::delete('/documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
    });
});
