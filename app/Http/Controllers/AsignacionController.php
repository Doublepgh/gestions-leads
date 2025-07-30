<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;

class AsignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asignacion::with(['lead', 'operador']);

        if ($search = $request->input('search')) {
            $query->whereHas('lead', fn($q) =>
                $q->where('nombre', 'like', "%{$search}%")
            )->orWhereHas('operador', fn($q) =>
                $q->where('name', 'like', "%{$search}%")
            );
        }
        $perPage = $request->input('per_page', 10);
        $asignaciones = $query->paginate($perPage);

        // Transformamos los datos antes de retornarlos
        $asignaciones->getCollection()->transform(function ($asignacion) {
            return [
                'id' => $asignacion->id,
                'lead' => $asignacion->lead?->nombre ?? 'No asignado',
                'operador' => $asignacion->operador?->name ?? 'No asignado',
                'asignado_en' => $asignacion->asignado_en,
                'cerrado_en' => $asignacion->cerrado_en,
            ];
        });

        // return response()->json(
        //     $query->paginate($perPage)
        // );
        return response()->json($asignaciones);
    }

    public function exportarPDF()
    {
        // Consulta general con relaciones
        $asignaciones = Asignacion::with(['lead', 'operador'])->get();

        // Otras ideas (para futuros filtros por operador, estado, fechas)
        // $asignaciones = Asignacion::whereNotNull('cerrado_en')->with(['lead', 'operador'])->get();

        $pdf = Pdf::loadView('pdf.asignaciones', compact('asignaciones'));
        return $pdf->download('reporte_asignaciones.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
