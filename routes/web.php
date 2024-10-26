<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Controladores
use App\Http\Controllers\{
    CargoController,
    ClienteController,
    EmpleadoController,
    EquipoTrabajoController,
    EspecializacionController,
    EstadoProyectoController,
    EstadoTareaController,
    FacturaController,
    ProyectoController,
    RecursoController,
    RecursoProyectoController,
    RelacionEstadoProyectoController,
    RelacionEstadoTareaController,
    TareaController,
    TipoEntornoController,
    HomeController,
    ProfileController
};

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Rutas de Verificación de Email
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/home');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | Rutas de Perfil
    |--------------------------------------------------------------------------
    */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy'); // Agregar esta línea
});
    /*
    |--------------------------------------------------------------------------
    | Rutas de Recursos Básicos
    |--------------------------------------------------------------------------
    */
    Route::resources([
        'cargo' => CargoController::class,
        'cliente' => ClienteController::class,
        'empleado' => EmpleadoController::class,
        'equipo_trabajo' => EquipoTrabajoController::class,
        'especializacion' => EspecializacionController::class,
        'estado_proyecto' => EstadoProyectoController::class,
        'estado_tarea' => EstadoTareaController::class,
        'factura' => FacturaController::class,
        'proyecto' => ProyectoController::class,
        'recurso' => RecursoController::class,
        'recurso_proyecto' => RecursoProyectoController::class,
        'relacion_estado_proyecto' => RelacionEstadoProyectoController::class,
        'relacion_estado_tarea' => RelacionEstadoTareaController::class,
        'tarea' => TareaController::class,
        'tipo_entorno' => TipoEntornoController::class,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Rutas Adicionales de Recursos
    |--------------------------------------------------------------------------
    */
    
    // Rutas de Recursos
    Route::prefix('recurso')->name('recurso.')->group(function () {
        Route::get('{recurso}/reporte-uso', [RecursoController::class, 'reporteUso'])->name('reporte-uso');
        Route::get('inventario', [RecursoController::class, 'inventario'])->name('inventario');
        Route::post('{recurso}/asignar-proyecto', [RecursoController::class, 'asignarAProyecto'])->name('asignar-proyecto');
        Route::post('{recurso}/actualizar-precio', [RecursoController::class, 'actualizarPrecio'])->name('actualizar-precio');
    });

    // Rutas de Empleados
    Route::prefix('empleado')->name('empleado.')->group(function () {
        Route::get('{empleado}/tareas', [EmpleadoController::class, 'tareas'])->name('tareas');
        Route::get('{empleado}/rendimiento', [EmpleadoController::class, 'rendimiento'])->name('rendimiento');
        Route::post('{empleado}/cambiar-equipo', [EmpleadoController::class, 'cambiarEquipo'])->name('cambiar-equipo');
        Route::post('{empleado}/asignar-cargo', [EmpleadoController::class, 'asignarCargo'])->name('asignar-cargo');
    });

    // Rutas de Clientes
    Route::prefix('cliente')->name('cliente.')->group(function () {
        Route::get('{cliente}/proyectos', [ClienteController::class, 'proyectos'])->name('proyectos');
        Route::get('{cliente}/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    });

    // Rutas de Proyectos
    Route::post('proyecto/{proyecto}/cambiar-estado', [ProyectoController::class, 'cambiarEstado'])
        ->name('proyecto.cambiarEstado');

    // Rutas de Facturas
    Route::prefix('factura')->name('factura.')->group(function () {
        Route::get('{factura}/generate-pdf', [FacturaController::class, 'generatePDF'])->name('generatePDF');
        Route::get('{factura}/preview', [FacturaController::class, 'preview'])->name('preview');
    });

    // Rutas de Tareas
    Route::prefix('tarea')->name('tarea.')->group(function () {
        Route::get('{tarea}/evidencia', [TareaController::class, 'verEvidencia'])->name('evidencia');
        Route::post('{tarea}/finalizar', [TareaController::class, 'finalizar'])->name('finalizar');
    });

    // Rutas de Equipos de Trabajo
    Route::prefix('equipo_trabajo')->name('equipo_trabajo.')->group(function () {
        Route::get('{equipo_trabajo}/rendimiento', [EquipoTrabajoController::class, 'rendimiento'])->name('rendimiento');
        Route::get('{equipo_trabajo}/proyectos-activos', [EquipoTrabajoController::class, 'proyectosActivos'])->name('proyectos-activos');
    });
});