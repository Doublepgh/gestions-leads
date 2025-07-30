<?php

namespace App\Http\Controllers;
use App\Models\Asignacion;
use Inertia\Inertia;
use App\Models\User;

use Illuminate\Http\Request;

class GraficaController extends Controller
{
    public function index()
    {
        // 1. Leads abiertos vs cerrados
        $abiertos = Asignacion::whereNull('cerrado_en')->count();
        $cerrados = Asignacion::whereNotNull('cerrado_en')->count();

        $abiertosVsCerrados = [
            'labels' => ['Abiertos', 'Cerrados'],
            'data' => [$abiertos, $cerrados],
        ];

        // 2. Asignaciones por operador
        $porOperador = Asignacion::selectRaw('operador_id, COUNT(*) as total')
            ->groupBy('operador_id')
            ->with('operador:id,name')
            ->get();

        $operadorLabels = $porOperador->map(fn($a) => $a->operador->name);
        $operadorData = $porOperador->map(fn($a) => $a->total);

        $asignacionesPorOperador = [
            'labels' => $operadorLabels,
            'data' => $operadorData,
        ];

        return Inertia::render('Graficas', [
            'abiertosVsCerrados' => $abiertosVsCerrados,
            'asignacionesPorOperador' => $asignacionesPorOperador,
        ]);
    }
}
