<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; 

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facturas = Factura::with(['proyecto.cliente'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return view('facturas.index', compact('facturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo proyectos que no tengan factura
        $proyectos = Proyecto::whereDoesntHave('factura')
            ->with(['cliente', 'recursos'])
            ->get();

        return view('facturas.create', compact('proyectos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto|unique:factura,proyecto_no_proyecto',
            'nit' => 'required|string|max:20',
            'total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $factura = Factura::create([
                'proyecto_no_proyecto' => $request->proyecto_no_proyecto,
                'nit' => $request->nit,
                'total' => $request->total,
                'fecha_emision' => now()
            ]);

            DB::commit();
            return redirect()->route('factura.index')
                           ->with('success', 'Factura creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la factura: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        $factura->load('proyecto.cliente', 'proyecto.recursos');
        
        $detalles = [
            'recursos' => $factura->proyecto->recursos->map(function($recurso) {
                return [
                    'nombre' => $recurso->nombre,
                    'cantidad' => $recurso->pivot->cantidad_asignada,
                    'precio_unitario' => $recurso->precio,
                    'subtotal' => $recurso->precio * $recurso->pivot->cantidad_asignada
                ];
            }),
            'subtotal_recursos' => $factura->proyecto->recursos->sum(function($recurso) {
                return $recurso->precio * $recurso->pivot->cantidad_asignada;
            }),
            'mano_obra' => $factura->proyecto->mano_obra,
            'total' => $factura->total
        ];

        return view('facturas.show', compact('factura', 'detalles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        return view('facturas.edit', compact('factura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'nit' => 'required|string|max:20',
            'total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $factura->update($request->only(['nit', 'total']));

            DB::commit();
            return redirect()->route('factura.show', $factura)
                           ->with('success', 'Factura actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        try {
            DB::beginTransaction();
            
            $factura->delete();
            
            DB::commit();
            return redirect()->route('factura.index')
                           ->with('success', 'Factura eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for the specified factura.
     */
    
     public function generatePDF(Factura $factura)
     {
         try {
             // Cargar las relaciones necesarias
             $factura->load(['proyecto.cliente', 'proyecto.recursos']);
             
             // Asegurarse de que la fecha sea un objeto Carbon
             if (is_string($factura->fecha_emision)) {
                 $factura->fecha_emision = Carbon::parse($factura->fecha_emision);
             }
 
             // Preparar los detalles de la factura usando el método del modelo
             $detalles = [
                 'recursos' => $factura->proyecto->recursos->map(function($recurso) {
                     return [
                         'nombre' => $recurso->nombre,
                         'cantidad' => $recurso->pivot->cantidad_asignada,
                         'precio_unitario' => $recurso->precio,
                         'subtotal' => $recurso->precio * $recurso->pivot->cantidad_asignada
                     ];
                 }),
                 'subtotal_recursos' => $factura->proyecto->recursos->sum(function($recurso) {
                     return $recurso->precio * $recurso->pivot->cantidad_asignada;
                 }),
                 'mano_obra' => $factura->proyecto->mano_obra,
                 'total' => $factura->total
             ];
 
             // Generar el PDF
             $pdf = PDF::loadView('facturas.pdf', compact('factura', 'detalles'))
                 ->setPaper('letter', 'portrait')
                 ->setOptions([
                     'isHtml5ParserEnabled' => true,
                     'isRemoteEnabled' => true,
                     'isPhpEnabled' => true,
                     'defaultFont' => 'dejavu sans',
                     'fontDir' => storage_path('fonts'),
                     'defaultMediaType' => 'screen',
                     'isFontSubsettingEnabled' => true,
                     'debugPng' => false,
                     'debugKeepTemp' => false,
                     'chroot' => public_path(),
                 ]);
 
             // Nombre del archivo
             $filename = 'factura-' . str_pad($factura->no_factura, 8, '0', STR_PAD_LEFT) . '.pdf';
 
             // Descargar el PDF
             return $pdf->download($filename);
 
         } catch (\Exception $e) {
             \Log::error('Error en generación de PDF: ' . $e->getMessage());
             \Log::error($e->getTraceAsString());
             
             return back()
                 ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
         }
     }
 


    /**
     * Calculate totals for a project.
     */
    public function calcularTotales(Proyecto $proyecto)
    {
        try {
            $totales = [
                'subtotal_recursos' => $proyecto->recursos->sum(function($recurso) {
                    return $recurso->precio * $recurso->pivot->cantidad_asignada;
                }),
                'mano_obra' => $proyecto->mano_obra,
                'total' => $proyecto->recursos->sum(function($recurso) {
                    return $recurso->precio * $recurso->pivot->cantidad_asignada;
                }) + $proyecto->mano_obra
            ];

            return response()->json($totales);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al calcular totales'], 500);
        }
    }

    /**
     * Preview factura before generating PDF.
     */
    public function preview(Factura $factura)
    {
        $factura->load('proyecto.cliente', 'proyecto.recursos');
        
        $detalles = [
            'recursos' => $factura->proyecto->recursos->map(function($recurso) {
                return [
                    'nombre' => $recurso->nombre,
                    'cantidad' => $recurso->pivot->cantidad_asignada,
                    'precio_unitario' => $recurso->precio,
                    'subtotal' => $recurso->precio * $recurso->pivot->cantidad_asignada
                ];
            }),
            'subtotal_recursos' => $factura->proyecto->recursos->sum(function($recurso) {
                return $recurso->precio * $recurso->pivot->cantidad_asignada;
            }),
            'mano_obra' => $factura->proyecto->mano_obra,
            'total' => $factura->total
        ];

        return view('facturas.preview', compact('factura', 'detalles'));
    }
    
}