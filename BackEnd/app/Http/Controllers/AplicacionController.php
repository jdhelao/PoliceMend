<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aplicaciones;

class AplicacionController extends Controller
{
    public function index() {
        $aplicaciones = Aplicaciones::where('ap_estado',true)->get();
        return response()->json($aplicaciones, 200);
    }

    public function create (Request $request) {
        if (isset($request->ap_nombre) and isset($request->ap_ruta)) {
            $aplicacion = new Aplicaciones;
            $aplicacion->ap_nombre = $request->ap_nombre;
            $aplicacion->ap_ruta = $request->ap_ruta;

            if (isset($request->ap_imagen))     { $usuario->ap_imagen   = $request->ap_imagen; }
            if (isset($request->ap_estado))     { $usuario->ap_estado   = $request->ap_estado; }

            $aplicacion->updated_at = null;
            $aplicacion->save();
            return response()->json([$aplicacion], 201);
        }
        else {
            return response()->json([], 400);
        }
    }

    public function read ($id) {
        $aplicacion = ($id === 'all') ? Aplicaciones::all() : Aplicaciones::where('ap_codigo',(int)$id)->get();
        return response()->json($aplicacion, (sizeof($aplicacion)?200:404));
    }

    public function update (Request $request) {
        if (Aplicaciones::where('ap_codigo', $request->ap_codigo)->exists()) {
            $aplicacion = Aplicaciones::find($request->ap_codigo);
            if (isset($request->ap_nombre))     { $usuario->ap_nombre   = $request->ap_nombre; }
            if (isset($request->ap_ruta))       { $usuario->ap_ruta     = $request->ap_ruta; }
            if (isset($request->ap_imagen))     { $usuario->ap_imagen   = $request->ap_imagen; }
            if (isset($request->ap_estado))     { $usuario->ap_estado   = $request->ap_estado; }
            $aplicacion->save();
            return response()->json([$aplicacion], 202);
        }
        else {
            return response()->json([], (!isset($request->ap_codigo)?400:404));
        }
    }

    /*
    get Apps by Profile
    */
    public function profile ($id) {
        $aplicaciones = Aplicaciones::join('aplicacion_perfil', 'aplicacion_perfil.ap_codigo', '=', 'aplicaciones.ap_codigo')
            ->join('perfiles', 'perfiles.pf_codigo', '=', 'aplicacion_perfil.pf_codigo')
            ->where('aplicaciones.ap_estado',true)
            ->where('aplicacion_perfil.ap_estado',true)
            ->where('perfiles.pf_estado',true)
            ->where('perfiles.pf_codigo',(int)$id)
            ->get('aplicaciones.*');
        return response()->json($aplicaciones, (sizeof($aplicaciones)?200:404));
    }
}
