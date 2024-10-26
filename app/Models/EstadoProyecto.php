<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoProyecto extends Model
{
    protected $table = 'estado_proyecto';
    protected $primaryKey = 'no_estado';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function proyectos()
    {
        return $this->belongsToMany(
            Proyecto::class,
            'relacion_estado_proyecto',
            'estado_proyecto_no_estado',
            'proyecto_no_proyecto'
        );
    }
}