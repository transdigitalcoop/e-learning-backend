<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Modulo;
use App\Models\User;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function completarModulo($usuarioId, $moduloId)
    {
        // Encontrar el usuario autenticado
        $user = User::find($usuarioId);
        // Encontrar la inscripción del usuario para el módulo actual
        $inscripcion = Inscripcion::where('usuario_id', $user->id)
            ->where('modulo_id', $moduloId)
            ->first();

        // Si la inscripción no existe, retornar un error
        if (!$inscripcion) {
            return response()->json(['error' => 'Inscripción no encontrada'], 404);
        }

        // Marcar el módulo como completado
        $inscripcion->completado = true;
        $inscripcion->save();

        // Buscar el siguiente módulo no completado del curso
        $siguienteModulo = Modulo::where('id_curso', $inscripcion->curso_id)
            ->whereDoesntHave('inscripciones', function ($query) use ($user) {
                $query->where('usuario_id', $user->id)
                      ->where('completado', true);
            })
            ->orderBy('id')
            ->first();

        // Si hay un siguiente módulo, actualizar la inscripción con el nuevo módulo
        if ($siguienteModulo) {
            $inscripcion->modulo_id = $siguienteModulo->id;
            $inscripcion->completado = false; // Marcamos que aún no ha sido completado
            $inscripcion->save();
        }

        return response()->json([
            'message' => 'Módulo completado con éxito',
            'siguiente_modulo_id' => $siguienteModulo ? $siguienteModulo->id : null
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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