<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia; // Importa Inertia si usas Inertia

class LeadController extends Controller
{
    public function index()
    {
        return Lead::latest()->get();
    }

    public function create()
    {
        // Obtener operadores con modo manual
        $operadoresManual = User::where('modo_asignacion', 'manual')
            ->whereHas('roles', fn($q) => $q->where('name', 'operador'))
            ->get(['id', 'name']); // 'name' porque en tu tabla users usas 'name'

        return Inertia::render('Leads/Create', [
            'operadores' => $operadoresManual,
        ]);
    }

    public function store(Request $request)
    {
        \Log::info('Solicitud recibida para registrar lead', $request->all());
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:100',
            'interes' => 'nullable|string',
            'estatus' => ['nullable', Rule::in(['abierto', 'en_proceso', 'cerrado'])], // valida asignacion_tipo
            'operador_id' => 'nullable|exists:users,id', // tabla correcta users
        ]);

        // Crear el lead
        $lead = Lead::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'empresa' => $request->empresa,
            'interes' => $request->interes,
            'estatus' => $request->estatus ?? 'abierto',// Usar el ID del usuario autenticado
            'operador_id' => $request->operador_id, // Asignar el ID del usuario autenticado
        ]);

        if ($request->asignacion_tipo === 'manual' && $request->operador_id) {
            // Asignación manual
            Asignacion::create([
                'lead_id' => $lead->id,
                'operador_id' => $request->operador_id,
            ]);
        } else {
            // Asignación automática
            $operadores = User::where('modo_asignacion', 'automatico')
                ->whereHas('roles', fn($q) => $q->where('name', 'operador'))
                ->get();

            if ($operadores->isNotEmpty()) {
                // Obtener operador con menos asignaciones
                $operador = $operadores->sortBy(fn($op) => $op->asignaciones()->count())->first();

                Asignacion::create([
                    'lead_id' => $lead->id,
                    'operador_id' => $operador->id,
                ]);
            }
        }

        return response()->json([
            'message' => 'Lead creado correctamente.',
            'lead' => $lead,
        ], 201);
    }

    public function show(string $id)
    {
        $lead = Lead::with('creador')->findOrFail($id);
        return response()->json($lead);
    }

    public function edit(Lead $lead)
    {
        return Inertia::render('Leads/Edit', [
            'lead' => $lead,
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'estatus' => 'required|string|max:50',
        ]);

        $lead->update($request->only(['nombre', 'correo', 'telefono', 'empresa', 'estatus']));

        return redirect()->route('dashboard')->with('success', 'Lead actualizado');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('dashboard')->with('success', 'Lead eliminado');
    }
}
