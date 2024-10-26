<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoTarea extends Model
{
    protected $table = 'estado_tarea';
    protected $primaryKey = 'no_estado';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'estado_tarea', 'no_estado');
    }
}