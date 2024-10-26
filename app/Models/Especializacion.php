<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especializacion extends Model
{
    protected $table = 'especializacion';
    protected $primaryKey = 'no_especializacion';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'no_especializacion', 'no_especializacion');
    }
}