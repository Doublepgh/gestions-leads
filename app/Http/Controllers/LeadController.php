<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
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
         $user = Auth::user();

    // Verifica si el usuario tiene el rol 'operador'
        if ($user->hasAnyRole(['admin', 'superadmin'])) {
            $leads = Lead::all();
        } else {
            $leads = Lead::where('operador_id', $user->id)->get();
        }

    return Inertia::render('Dashboard', [
        'leads' => $leads,
        'auth' => [
            'user' => $user,
            'roles' => $user->getRoleNames()
        ]
    ]);
    }

    public function create()
    {
        // Obtener operadores con modo manual
        $operadoresManual = User::where('modo_asignacion', 'manual')
            ->where('activo', 1)
            ->whereHas('roles', fn($q) => $q->where('name', 'operador'))
            ->get(['id', 'name']);

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
            'estatus' => ['nullable', Rule::in(['abierto', 'en_proceso', 'cerrado'])],
            'operador_id' => 'nullable|exists:users,id',
            // 'asignacion_tipo' => ['required', Rule::in(['manual', 'automatico'])],
        ]);

        // Inicializar sin operador asignado
        $operador_id = null;

        if ($request->asignacion_tipo === 'manual' && $request->filled('operador_id')) {
            $operador_id = $request->operador_id;
        }

        if ($request->asignacion_tipo === 'automatico') {
            $operadores = User::where('modo_asignacion', 'automatico')
                ->where('activo', 1)
                ->whereHas('roles', fn($q) => $q->where('name', 'operador'))
                ->get();

            if ($operadores->isNotEmpty()) {
                $operador = $operadores->sortBy(fn($op) => $op->asignaciones()->count())->first();
                $operador_id = $operador->id;
            }
        }

        \Log::info('Operador asignado final', ['operador_id' => $operador_id]); // 🧪 para depuración

        $lead = Lead::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'empresa' => $request->empresa,
            'interes' => $request->interes,
            'estatus' => $request->estatus ?? 'abierto',
            'operador_id' => $request->operador_id,
        ]);

        if ($request->filled('operador_id')) {
            Asignacion::create([
                'lead_id' => $lead->id,
                'operador_id' => $request->operador_id,
                'asignado_en' => now(),
            ]);
        }
    }

    public function show(Lead $lead)
    {
        $user = auth()->user();

    if ($user->hasRole('admin') || $lead->operador_id === $user->id) {
        return response()->json($lead);
    }

    return response()->json(['message' => 'No autorizado'], 403);
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
