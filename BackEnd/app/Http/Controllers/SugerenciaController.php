<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugerencias;

class SugerenciaController extends Controller
{
    public function index() {
        $sugerencias = Sugerencias::where('su_estado',true)->get();
        return response()->json($sugerencias, 200);
    }

    public function create (Request $request) {
        if (isset($request->su_tipo) && isset($request->sc_codigo) && isset($request->su_contacto) && isset($request->su_nombres) && isset($request->su_apellidos) && isset($request->su_detalles)
        && strlen($request->su_tipo) && strlen($request->sc_codigo) && strlen($request->su_contacto) && strlen($request->su_nombres) && strlen($request->su_apellidos) && strlen($request->su_detalles)
        ) {
            $sugerencia = new Sugerencias;
            $sugerencia->su_tipo        = $request->su_tipo;
            $sugerencia->sc_codigo      = $request->sc_codigo;
            $sugerencia->su_contacto    = $request->su_contacto;
            $sugerencia->su_nombres     = $request->su_nombres;
            $sugerencia->su_apellidos   = $request->su_apellidos;
            $sugerencia->su_detalles    = $request->su_detalles;
            $sugerencia->su_estado      = is_null($request->su_estado) ? true : $request->su_estado;
            $sugerencia->save();
            return response()->json($sugerencia, 201);
        }
        else {
            return response()->json(null, 400);
        }
    }

    public function read ($id) {
        $sugerencia = ($id === 'all') ? Sugerencias::all() : Sugerencias::where('su_codigo',(int)$id)->get();
        return response()->json($sugerencia, (sizeof($sugerencia)?200:404));
    }

    public function update (Request $request) {
        if (Sugerencias::where('su_codigo', $request->su_codigo)->exists()) {
            $sugerencia = Sugerencias::find($request->su_codigo);
            $sugerencia->su_tipo        = $request->su_tipo;
            $sugerencia->sc_codigo      = $request->sc_codigo;
            $sugerencia->su_contacto    = $request->su_contacto;
            $sugerencia->su_nombres     = $request->su_nombres;
            $sugerencia->su_apellidos   = $request->su_apellidos;
            $sugerencia->su_detalles    = $request->su_detalles;
            $sugerencia->su_estado      = is_null($request->su_estado) ? true : $request->su_estado;
            $sugerencia->save();
            return response()->json($sugerencia, 202);
        }
        else {
            return response()->json(null, (!isset($request->su_codigo)?400:404));
        }
    }
}
