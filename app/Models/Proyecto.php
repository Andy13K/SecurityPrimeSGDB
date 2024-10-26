<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    protected $table = 'proyecto';
    protected $primaryKey = 'no_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'no_tipo_entorno',
        'no_equipo',
        'no_cliente',
        'mano_obra'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'mano_obra' => 'decimal:2'
    ];

    // Método helper para formatear fechas
    public function formatDate($date)
    {
        try {
            if (is_null($date)) return 'N/A';
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'no_cliente', 'no_cliente');
    }

    public function equipo()
    {
        return $this->belongsTo(EquipoTrabajo::class, 'no_equipo', 'no_equipo');
    }

    public function tipoEntorno()
    {
        return $this->belongsTo(TipoEntorno::class, 'no_tipo_entorno', 'no_entorno');
    }

    public function estado()
    {
        return $this->belongsToMany(
            EstadoProyecto::class,
            'relacion_estado_proyecto',
            'proyecto_no_proyecto',
            'estado_proyecto_no_estado'
        );
    }

    public function recursos()
    {
        return $this->belongsToMany(
            Recurso::class,
            'recurso_proyecto',
            'proyecto_no_proyecto',
            'recurso_no_recurso'
        )->withPivot('cantidad_asignada', 'fecha');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'no_proyecto', 'no_proyecto');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'proyecto_no_proyecto', 'no_proyecto');
    }

    // Accessors para fechas
    public function getFechaInicioFormateadaAttribute()
    {
        return $this->formatDate($this->fecha_inicio);
    }

    public function getFechaFinFormateadaAttribute()
    {
        return $this->formatDate($this->fecha_fin);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->where('nombre', 'EN PROCESO');
        });
    }

    public function scopeFinalizados($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->where('nombre', 'FINALIZADO');
        });
    }

    public function scopeRetrasados($query)
    {
        return $query->where('fecha_fin', '<', Carbon::now())
                    ->whereHas('estado', function($q) {
                        $q->where('nombre', '!=', 'FINALIZADO');
                    });
    }

    // Métodos de cálculo
    public function calcularCostoTotal()
    {
        $costoRecursos = $this->recursos->sum(function($recurso) {
            return $recurso->costo * $recurso->pivot->cantidad_asignada;
        });
        return $costoRecursos + $this->mano_obra;
    }

    public function calcularPrecioTotal()
    {
        $precioRecursos = $this->recursos->sum(function($recurso) {
            return $recurso->precio * $recurso->pivot->cantidad_asignada;
        });
        return $precioRecursos + ($this->mano_obra * 1.3); // 30% de margen en mano de obra
    }

    public function calcularMargen()
    {
        return $this->calcularPrecioTotal() - $this->calcularCostoTotal();
    }

    public function calcularPorcentajeCompletado()
    {
        $totalTareas = $this->tareas()->count();
        if ($totalTareas === 0) return 0;

        $tareasCompletadas = $this->tareas()
            ->whereHas('estado', function($q) {
                $q->where('nombre', 'FINALIZADA');
            })->count();

        return ($tareasCompletadas / $totalTareas) * 100;
    }

    // Métodos de estado
    public function estaEnProceso()
    {
        return $this->estado->where('nombre', 'EN PROCESO')->count() > 0;
    }

    public function estaFinalizado()
    {
        return $this->estado->where('nombre', 'FINALIZADO')->count() > 0;
    }

    public function estaRetrasado()
    {
        return Carbon::parse($this->fecha_fin)->isPast() && !$this->estaFinalizado();
    }

    // Métodos de gestión
    public function cambiarEstado($nuevoEstado)
    {
        $this->estado()->sync([EstadoProyecto::where('nombre', $nuevoEstado)->first()->no_estado]);
    }

    public function asignarRecurso($recursoId, $cantidad)
    {
        $this->recursos()->attach($recursoId, [
            'cantidad_asignada' => $cantidad,
            'fecha' => Carbon::now()
        ]);
    }

    public function actualizarRecurso($recursoId, $cantidad)
    {
        $this->recursos()->updateExistingPivot($recursoId, [
            'cantidad_asignada' => $cantidad,
            'fecha' => Carbon::now()
        ]);
    }

    // Métodos de información
    public function getDuracionEnDias()
    {
        return Carbon::parse($this->fecha_inicio)->diffInDays(Carbon::parse($this->fecha_fin));
    }

    public function getDiasRestantes()
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->fecha_fin), false);
    }

    public function getResumenRecursos()
    {
        return $this->recursos->map(function($recurso) {
            return [
                'nombre' => $recurso->nombre,
                'cantidad' => $recurso->pivot->cantidad_asignada,
                'costo_total' => $recurso->costo * $recurso->pivot->cantidad_asignada,
                'precio_total' => $recurso->precio * $recurso->pivot->cantidad_asignada
            ];
        });
    }
}