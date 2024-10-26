<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'no_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'correo',
        'telefono'
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'no_cliente', 'no_cliente');
    }
}