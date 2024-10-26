<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'recurso';
    protected $primaryKey = 'no_recurso';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'precio',
        'costo'
    ];

    public function proyectos()
    {
        return $this->belongsToMany(
            Proyecto::class,
            'recurso_proyecto',
            'recurso_no_recurso',
            'proyecto_no_proyecto'
        )->withPivot('cantidad_asignada', 'fecha');
    }
}