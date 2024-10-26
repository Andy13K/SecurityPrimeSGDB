<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelacionEstadoProyecto extends Model
{
    protected $table = 'relacion_estado_proyecto';
    
    // No usar timestamps ya que la tabla no tiene estos campos
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'estado_proyecto_no_estado',
        'proyecto_no_proyecto'
    ];

    // Definir la llave primaria compuesta
    protected $primaryKey = ['estado_proyecto_no_estado', 'proyecto_no_proyecto'];
    
    // Indicar que la llave primaria no es autoincremental
    public $incrementing = false;

    // Relación con el proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_no_proyecto', 'no_proyecto');
    }

    // Relación con el estado del proyecto
    public function estado()
    {
        return $this->belongsTo(EstadoProyecto::class, 'estado_proyecto_no_estado', 'no_estado');
    }

    // Obtener el historial de estados de un proyecto
    public static function getHistorialProyecto($proyectoId)
    {
        return self::where('proyecto_no_proyecto', $proyectoId)
            ->with('estado')
            ->orderBy('estado_proyecto_no_estado')
            ->get();
    }

    // Verificar si un proyecto tiene un estado específico
    public static function tieneEstado($proyectoId, $estadoId)
    {
        return self::where([
            'proyecto_no_proyecto' => $proyectoId,
            'estado_proyecto_no_estado' => $estadoId
        ])->exists();
    }

    // Cambiar el estado de un proyecto
    public static function cambiarEstado($proyectoId, $nuevoEstadoId)
    {
        // Primero eliminamos cualquier estado existente
        self::where('proyecto_no_proyecto', $proyectoId)->delete();
        
        // Luego creamos la nueva relación
        return self::create([
            'proyecto_no_proyecto' => $proyectoId,
            'estado_proyecto_no_estado' => $nuevoEstadoId
        ]);
    }

    // Obtener proyectos por estado
    public static function getProyectosPorEstado($estadoId)
    {
        return self::where('estado_proyecto_no_estado', $estadoId)
            ->with('proyecto')
            ->get()
            ->pluck('proyecto');
    }

    // Sobreescribir el método setKeysForSaveQuery para manejar la llave primaria compuesta
    protected function setKeysForSaveQuery($query)
    {
        $query->where('estado_proyecto_no_estado', $this->getAttribute('estado_proyecto_no_estado'))
              ->where('proyecto_no_proyecto', $this->getAttribute('proyecto_no_proyecto'));
        
        return $query;
    }

    // Método para validar si una combinación de proyecto y estado es válida
    public static function esValido($proyectoId, $estadoId)
    {
        return Proyecto::where('no_proyecto', $proyectoId)->exists() &&
               EstadoProyecto::where('no_estado', $estadoId)->exists();
    }

    // Método para obtener el estado actual de un proyecto
    public static function getEstadoActual($proyectoId)
    {
        return self::where('proyecto_no_proyecto', $proyectoId)
            ->with('estado')
            ->first();
    }

    // Método para obtener estadísticas de estados
    public static function getEstadisticas()
    {
        return self::selectRaw('estado_proyecto_no_estado, COUNT(*) as total')
            ->with('estado')
            ->groupBy('estado_proyecto_no_estado')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->estado->nombre => $item->total];
            });
    }

    // Para evitar errores con la llave primaria compuesta
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyName()
    {
        return ['estado_proyecto_no_estado', 'proyecto_no_proyecto'];
    }

    public function getKeyType()
    {
        return 'int';
    }
}