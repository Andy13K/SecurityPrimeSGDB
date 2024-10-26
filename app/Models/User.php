<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cargo_id',       // Si tienes un campo para el cargo
        'is_active',      // Para estado del usuario
        'last_login_at',  // Último inicio de sesión
        'profile_photo',  // Si manejas foto de perfil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relación con el empleado asociado al usuario
     */
    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }

    /**
     * Relación con el cargo del usuario
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * Relación con el equipo de trabajo a través del empleado
     */
    public function equipoTrabajo()
    {
        return $this->hasOneThrough(EquipoTrabajo::class, Empleado::class);
    }

    /**
     * Obtener las tareas asignadas al usuario a través del empleado
     */
    public function tareas()
    {
        return $this->hasManyThrough(Tarea::class, Empleado::class);
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->cargo && $this->cargo->nombre === $role;
    }

    /**
     * Verifica si el usuario está activo
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Registra el último inicio de sesión
     */
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now()
        ]);
    }

    /**
     * Desactiva el usuario
     */
    public function deactivate()
    {
        $this->update([
            'is_active' => false
        ]);
    }

    /**
     * Activa el usuario
     */
    public function activate()
    {
        $this->update([
            'is_active' => true
        ]);
    }

    /**
     * Cambia la contraseña del usuario
     */
    public function changePassword($newPassword)
    {
        $this->update([
            'password' => Hash::make($newPassword)
        ]);
    }

    /**
     * Obtiene el nombre completo del usuario
     */
    public function getFullNameAttribute()
    {
        return $this->empleado ? $this->empleado->nombre_completo : $this->name;
    }

    /**
     * Verifica si el usuario tiene permisos de administrador
     */
    public function isAdmin()
    {
        return $this->hasRole('Administrador');
    }

    /**
     * Boot method para el modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Antes de eliminar un usuario
        static::deleting(function ($user) {
            // Eliminar relaciones asociadas si es necesario
            if ($user->empleado) {
                $user->empleado->delete();
            }
        });
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios por cargo
     */
    public function scopeByCargo($query, $cargoId)
    {
        return $query->where('cargo_id', $cargoId);
    }

    /**
     * Verifica si el usuario necesita cambiar su contraseña
     */
    public function needsPasswordChange()
    {
        // Implementa tu lógica aquí
        // Por ejemplo, verificar si la contraseña no ha sido cambiada en los últimos 90 días
        return false;
    }
}