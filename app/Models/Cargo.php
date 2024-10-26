<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'no_cargo';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'cargo_no_cargo', 'no_cargo');
    }
}