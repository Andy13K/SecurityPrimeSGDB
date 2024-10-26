<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelacionEstadoTarea extends Model
{
    protected $table = 'relacion_estado_tarea';
    public $timestamps = false;

    protected $fillable = [
        'tarea_no_tarea',
        'proyecto_no_proyecto'
    ];

    // Relación con Tarea
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_no_tarea', 'no_tarea');
    }

    // Relación con Proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_no_proyecto', 'no_proyecto');
    }

    // Métodos útiles para obtener tareas por proyecto
    public static function tareasDelProyecto($proyectoId)
    {
        return self::where('proyecto_no_proyecto', $proyectoId)
                   ->with('tarea')
                   ->get();
    }

    // Método para verificar si una tarea pertenece a un proyecto
    public static function verificarPertenencia($tareaId, $proyectoId)
    {
        return self::where('tarea_no_tarea', $tareaId)
                   ->where('proyecto_no_proyecto', $proyectoId)
                   ->exists();
    }
}