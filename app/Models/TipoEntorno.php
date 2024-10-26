<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEntorno extends Model
{
    protected $table = 'tipo_entorno';
    protected $primaryKey = 'no_entorno';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'no_tipo_entorno', 'no_entorno');
    }
}