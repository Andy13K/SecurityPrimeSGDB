<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoTrabajo extends Model
{
    protected $table = 'equipo_trabajo';
    protected $primaryKey = 'no_equipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'supervisor'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'no_equipo', 'no_equipo');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'no_equipo', 'no_equipo');
    }
}