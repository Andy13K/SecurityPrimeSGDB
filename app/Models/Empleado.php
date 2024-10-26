<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'no_empleado';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'direccion',
        'no_equipo',
        'no_especializacion',
        'cargo_no_cargo'
    ];

    public function equipo()
    {
        return $this->belongsTo(EquipoTrabajo::class, 'no_equipo', 'no_equipo');
    }

    public function especializacion()
    {
        return $this->belongsTo(Especializacion::class, 'no_especializacion', 'no_especializacion');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_no_cargo', 'no_cargo');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'empleado_no', 'no_empleado');
    }
}