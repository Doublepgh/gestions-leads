<?php

namespace App\Http\Controllers;
use App\Models\Lead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lead::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100',
        'correo' => 'nullable|email|max:100',
        'telefono' => 'nullable|string|max:20',
        'empresa' => 'nullable|string|max:100',
        'interes' => 'nullable|string',
        'estatus' => ['nullable', Rule::in(['abierto', 'en_proceso', 'cerrado'])],
        'asignar_manual' => 'nullable|boolean',
        'operador_id' => 'nullable|exists:usuarios,id',
    ]);

    $lead = Lead::create([
        'nombre' => $request->nombre,
        'correo' => $request->correo,
        'telefono' => $request->telefono,
        'empresa' => $request->empresa,
        'interes' => $request->interes,
        'estatus' => $request->estatus ?? 'abierto',
        'creado_por' => Auth::id(), // Verifica que el usuario esté autenticado
    ]);

    return response()->json([
        'message' => 'Lead creado correctamente.',
        'lead' => $lead,
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = Lead::with('creador')->findOrFail($id);
        return response()->json($lead);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'correo' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:100',
            'interes' => 'nullable|string',
            'estatus' => ['nullable', Rule::in(['abierto', 'en_proceso', 'cerrado'])],
        ]);

        $lead->update($request->all());

        return response()->json([
            'message' => 'Lead actualizado correctamente.',
            'lead' => $lead
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'message' => 'Lead eliminado correctamente.'
        ]);
    }
}
