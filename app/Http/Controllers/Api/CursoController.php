<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Leccion;
use App\Models\Modulo;
use App\Models\User;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::where('id_estado', 1)->get();

        if ($cursos->isEmpty()) {
            $data = [
                'message' => "No se han encontrado cursos",
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        return response()->json($cursos);
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
        $curso = Curso::where('uuid', $id)->first();

        if (!$curso) {
            $data = (object) [
                'message' => 'No se encontró el curso',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $modulos = Modulo::where('id_curso', $curso->id)->get();

        if ($modulos->isEmpty()) {
            $data = (object) [
                'message' => 'No se encontraron modulos',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $data = (object) [
            'curso' => $curso,
            'modulos' => $modulos
        ];

        return response()->json($data);
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

    /**
     * Inscribir usuarios en cursos
     */

     public function inscribir($curso, $usuario)
     {
         $usuarioI = User::where('uuid', $usuario)->firstOrFail();
         $cursoI = Curso::where('uuid', $curso)->firstOrFail();
     
         // Verificar si el usuario ya está inscrito en el curso
         if ($usuarioI->cursos()->where('curso_id', $cursoI->id)->exists()) {  // Aquí se usa el ID del curso
             return response()->json([
                 'message' => 'Usuario ya está inscrito en el curso',
                 'status' => 400
             ], 400);
         }
     
         // Obtener el primer módulo del curso
         $primerModulo = $cursoI->modulos()->orderBy('orden')->first();
     
         if (!$primerModulo) {
             return response()->json([
                 'message' => 'El curso no tiene módulos disponibles',
                 'status' => 400
             ], 400);
         }
     
         // Inscribir al usuario en el curso y asignar el primer módulo
         $usuarioI->cursos()->attach($cursoI, ['modulo_id' => $primerModulo->id]);
     
         return response()->json([
             'message' => 'Usuario inscrito en el curso',
             'status' => 200
         ]);
     }
     
}
