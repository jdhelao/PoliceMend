<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfiles;

class PerfilController extends Controller
{
    public function index() {
        $perfiles = Perfiles::where('pf_estado',true)->get();
        return response()->json($perfiles, 200);
    }

    public function create (Request $request) {
        if (isset($request->pf_nombre)) {
            $perfil = new Perfiles;
            $perfil->pf_nombre = $request->pf_nombre;
            $perfil->pf_estado = is_null($request->pf_estado) ? true : $request->pf_estado;
            $perfil->updated_at = null;
            $perfil->save();
            return response()->json([$perfil], 201);
        }
        else {
            return response()->json([], 400);
        }
    }

    public function read ($id) {
        /*$perfil = ($id === 'all') ? Perfiles::all() : Perfiles::find($id)->get();
        if (!empty($perfil)) {
            return response()->json($perfil, 200);
        }
        else {
            return response()->json([], 404);
        }
        */
        $perfil = ($id === 'all') ? Perfiles::all() : Perfiles::where('pf_codigo',(int)$id)->get();
        return response()->json($perfil, (sizeof($perfil)?200:404));
    }

    public function update (Request $request) {
        if (Perfiles::where('pf_codigo', $request->pf_codigo)->exists()) {
            $perfil = Perfiles::find($request->pf_codigo);
            $perfil->pf_nombre = is_null($request->pf_nombre) ? $perfil->pf_nombre : $request->pf_nombre;
            $perfil->pf_estado = is_null($request->pf_estado) ? $perfil->pf_estado : $request->pf_estado;
            $perfil->save();
            return response()->json([$perfil], 202);
        }
        else {
            return response()->json([], (!isset($request->pf_codigo)?400:404));
        }
    }
}
