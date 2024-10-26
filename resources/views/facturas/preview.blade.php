@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Botones de acción -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <a href="{{ route('factura.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
            <h1 class="text-2xl font-bold">Vista Previa Factura #{{ str_pad($factura->no_factura, 8, '0', STR_PAD_LEFT) }}</h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('factura.generatePDF', $factura) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-download mr-2"></i>Descargar PDF
            </a>
            @if($factura->getEstado() === 'VIGENTE')
            <a href="{{ route('factura.edit', $factura) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            @endif
        </div>
    </div>

    <!-- Contenido de la factura -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <!-- Encabezado -->
        <div class="text-center mb-6 border-b pb-4">
            <h2 class="text-xl font-bold mb-2">FACTURA</h2>
            <h3 class="text-lg">No. {{ str_pad($factura->no_factura, 8, '0', STR_PAD_LEFT) }}</h3>
        </div>

        <!-- Información de la factura y cliente -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Datos de la factura -->
            <div class="border rounded-lg p-4">
                <h4 class="font-bold mb-3">Datos de Factura</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="text-gray-600">Fecha de Emisión:</div>
                    <div>
                        @if(is_string($factura->fecha_emision))
                            {{ date('d/m/Y', strtotime($factura->fecha_emision)) }}
                        @else
                            {{ $factura->fecha_emision->format('d/m/Y') }}
                        @endif
                    </div>
                    <div class="text-gray-600">Estado:</div>
                    <div>
                        <span class="px-2 py-1 rounded-full text-sm {{ $factura->getEstado() === 'VIGENTE' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $factura->getEstado() }}
                        </span>
                    </div>
                    <div class="text-gray-600">NIT:</div>
                    <div>{{ $factura->nit }}</div>
                </div>
            </div>

        <!-- Detalle de recursos -->
        <div class="mb-6">
            <h4 class="font-bold mb-3">Detalle de Factura</h4>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left">Descripción</th>
                            <th class="px-4 py-2 text-center">Cantidad</th>
                            <th class="px-4 py-2 text-right">Precio Unitario</th>
                            <th class="px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles['recursos'] as $recurso)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $recurso['nombre'] }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($recurso['cantidad'], 0) }}</td>
                            <td class="px-4 py-2 text-right">Q {{ number_format($recurso['precio_unitario'], 2) }}</td>
                            <td class="px-4 py-2 text-right">Q {{ number_format($recurso['subtotal'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="font-bold">
                        <tr class="border-t">
                            <td colspan="3" class="px-4 py-2 text-right">Subtotal Recursos:</td>
                            <td class="px-4 py-2 text-right">Q {{ number_format($detalles['subtotal_recursos'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right">Mano de Obra:</td>
                            <td class="px-4 py-2 text-right">Q {{ number_format($detalles['mano_obra'], 2) }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-4 py-2 text-right">TOTAL:</td>
                            <td class="px-4 py-2 text-right">Q {{ number_format($factura->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="border-t pt-4 text-sm text-gray-600">
            <p>Factura generada el {{ now()->format('d/m/Y H:i:s') }}</p>
            @if($factura->estaVencida())
            <p class="text-red-600 mt-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Esta factura está vencida
            </p>
            @endif
        </div>
    </div>

    <!-- Pie de página con acciones adicionales -->
    <div class="flex justify-end space-x-2">
        <form action="{{ route('factura.destroy', $factura) }}" method="POST" class="inline" 
              onsubmit="return confirm('¿Está seguro de que desea eliminar esta factura?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash mr-2"></i>Eliminar Factura
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
    .btn {
        @apply px-4 py-2 rounded-lg font-medium transition-colors duration-150;
    }
    .btn-primary {
        @apply bg-blue-600 text-white hover:bg-blue-700;
    }
    .btn-secondary {
        @apply bg-gray-500 text-white hover:bg-gray-600;
    }
    .btn-warning {
        @apply bg-yellow-500 text-white hover:bg-yellow-600;
    }
    .btn-danger {
        @apply bg-red-600 text-white hover:bg-red-700;
    }
</style>
@endpush
@endsection