<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecursoProyecto extends Model
{
    protected $table = 'recurso_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'recurso_no_recurso',
        'proyecto_no_proyecto',
        'cantidad_asignada',
        'fecha'
    ];

    protected $dates = ['fecha'];

    // Relación con Proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_no_proyecto', 'no_proyecto');
    }

    // Relación con Recurso
    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'recurso_no_recurso', 'no_recurso');
    }

    // Métodos útiles
    public function calcularSubtotal()
    {
        return $this->cantidad_asignada * $this->recurso->precio;
    }

    public function calcularCosto()
    {
        return $this->cantidad_asignada * $this->recurso->costo;
    }

    // Scopes útiles
    public function scopePorProyecto($query, $proyectoId)
    {
        return $query->where('proyecto_no_proyecto', $proyectoId);
    }

    public function scopePorRecurso($query, $recursoId)
    {
        return $query->where('recurso_no_recurso', $recursoId);
    }

    // Método para verificar disponibilidad
    public function verificarDisponibilidad()
    {
        // Aquí podrías implementar la lógica para verificar si hay suficiente cantidad
        // del recurso disponible para la asignación
        return true;
    }
}