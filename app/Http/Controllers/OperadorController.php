<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use PHPUnit\Framework\Constraint\Operator;

class OperadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operadores = User::role('operador')->get();

        return Inertia::render('Operadores/Index', [
            'operadores' => $operadores,
        ]);
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
    public function update(Request $request, $id)
        {
            $operador = User::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $id,
                'username' => 'required|string|max:50|unique:users,username,' . $id,
                'activo' => 'required|boolean',
            ]);

            $operador->update($validated);

            return response()->json($operador, 200);
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        \Log::info("Intentando eliminar operador con ID: $id");
        $operador = User::findOrFail($id);
    $operador->delete();

    \Log::info("Operador eliminado con éxito: $id");

    return response()->json(null, 204);
    }
}
