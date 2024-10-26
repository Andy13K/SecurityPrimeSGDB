<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'factura';
    protected $primaryKey = 'no_factura';
    public $timestamps = true;

    protected $fillable = [
        'proyecto_no_proyecto',
        'fecha_emision',
        'total',
        'nit'
    ];

    protected $dates = ['fecha_emision', 'created_at', 'updated_at'];

    // Relación con el proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_no_proyecto', 'no_proyecto');
    }

    // Atributos calculados
    protected $appends = ['subtotal_recursos', 'mano_obra'];

    // Obtener el subtotal de recursos
    public function getSubtotalRecursosAttribute()
    {
        return $this->proyecto->recursos->sum(function($recurso) {
            return $recurso->precio * $recurso->pivot->cantidad_asignada;
        });
    }

    // Obtener el costo de mano de obra
    public function getManoObraAttribute()
    {
        return $this->proyecto->mano_obra ?? 0;
    }

    // Métodos de ayuda
    public function getDetallesRecursos()
    {
        return $this->proyecto->recursos->map(function($recurso) {
            return [
                'nombre' => $recurso->nombre,
                'cantidad' => $recurso->pivot->cantidad_asignada,
                'precio_unitario' => $recurso->precio,
                'subtotal' => $recurso->precio * $recurso->pivot->cantidad_asignada
            ];
        });
    }

    // Generar número de factura formateado
    public function getNumeroFacturaFormateado()
    {
        return str_pad($this->no_factura, 8, '0', STR_PAD_LEFT);
    }

    // Calcular impuestos si es necesario
    public function calcularImpuestos()
    {
        return $this->total * 0.12; // 12% de IVA, ajustar según necesidad
    }

    // Validar NIT
    public static function validarNit($nit)
    {
        // Eliminar guiones y espacios
        $nit = str_replace(['-', ' '], '', $nit);
        
        // Validar longitud
        if (strlen($nit) < 8 || strlen($nit) > 20) {
            return false;
        }

        // Aquí puedes agregar más validaciones específicas del NIT
        return true;
    }

    // Scope para facturas por período
    public function scopePorPeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_emision', [$fechaInicio, $fechaFin]);
    }

    // Scope para facturas por cliente
    public function scopePorCliente($query, $clienteId)
    {
        return $query->whereHas('proyecto', function($q) use ($clienteId) {
            $q->where('no_cliente', $clienteId);
        });
    }

    // Obtener el total en letras
    public function getTotalEnLetras()
    {
        // Aquí puedes implementar la lógica para convertir números a letras
        // o usar una librería como NumberFormatter
        return $this->total;
    }

    // Boot method para eventos del modelo
    protected static function boot()
    {
        parent::boot();

        // Antes de crear
        static::creating(function ($factura) {
            if (empty($factura->fecha_emision)) {
                $factura->fecha_emision = now();
            }
        });

        // Después de crear
        static::created(function ($factura) {
            // Aquí puedes agregar lógica adicional después de crear la factura
            // Por ejemplo, actualizar estadísticas, enviar notificaciones, etc.
        });
    }

    // Verificar si la factura está vencida
    public function estaVencida()
    {
        return \Carbon\Carbon::parse($this->fecha_emision)
            ->addDays(30)
            ->isPast();
    }

    // Obtener el estado de la factura
    public function getEstado()
    {
        if ($this->estaVencida()) {
            return 'VENCIDA';
        }
        return 'VIGENTE';
    }
}