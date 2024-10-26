<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tarea extends Model
{
    protected $table = 'tarea';
    protected $primaryKey = 'no_tarea';
    public $timestamps = false;

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'estado_tarea',
        'empleado_no',
        'comprobacion',
        'no_proyecto'
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_fin'
    ];

    // Relaciones
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_no', 'no_empleado');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoTarea::class, 'estado_tarea', 'no_estado');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'no_proyecto', 'no_proyecto');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->whereIn('nombre', ['CREADA', 'ASIGNADA']);
        });
    }

    public function scopeRetrasadas($query)
    {
        return $query->where('fecha_fin', '<', Carbon::now())
                    ->whereHas('estado', function($q) {
                        $q->where('nombre', '!=', 'FINALIZADA');
                    });
    }

    public function scopePorEmpleado($query, $empleadoId)
    {
        return $query->where('empleado_no', $empleadoId);
    }

    // Métodos de estado
    public function estaAtrasada()
    {
        return $this->fecha_fin < Carbon::now() && !$this->estaFinalizada();
    }

    public function estaFinalizada()
    {
        return $this->estado->nombre === 'FINALIZADA';
    }

    public function estaAsignada()
    {
        return $this->estado->nombre === 'ASIGNADA';
    }

    // Métodos de gestión
    public function asignarEmpleado($empleadoId)
    {
        $this->empleado_no = $empleadoId;
        $this->estado_tarea = EstadoTarea::where('nombre', 'ASIGNADA')->first()->no_estado;
        $this->save();
    }

    public function marcarComoFinalizada()
    {
        $this->estado_tarea = EstadoTarea::where('nombre', 'FINALIZADA')->first()->no_estado;
        $this->comprobacion = 'Completado';
        $this->save();
    }

    public function actualizarComprobacion($comprobacion)
    {
        $this->comprobacion = $comprobacion;
        $this->save();
    }

    // Métodos de información
    public function getDiasRestantes()
    {
        return Carbon::now()->diffInDays($this->fecha_fin, false);
    }

    public function getDuracionEstimada()
    {
        // Asegurarnos de que ambas fechas son objetos Carbon
        $fechaInicio = is_string($this->fecha_inicio) ? Carbon::parse($this->fecha_inicio) : $this->fecha_inicio;
        $fechaFin = is_string($this->fecha_fin) ? Carbon::parse($this->fecha_fin) : $this->fecha_fin;
        
        return $fechaInicio->diffInDays($fechaFin);
    }
    public function getPrioridad()
    {
        if ($this->estaAtrasada()) return 'Alta';
        if ($this->getDiasRestantes() <= 2) return 'Media';
        return 'Normal';
    }

    public function getEstadoFormateado()
    {
        $estado = $this->estado->nombre;
        $diasRestantes = $this->getDiasRestantes();

        if ($this->estaAtrasada()) {
            return 'Atrasada - ' . abs($diasRestantes) . ' días';
        }

        return match($estado) {
            'FINALIZADA' => 'Completada',
            'ASIGNADA' => 'En proceso - ' . $diasRestantes . ' días restantes',
            default => 'Pendiente'
        };
    }
}