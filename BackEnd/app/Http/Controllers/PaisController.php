<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paises;

class PaisController extends Controller
{
    public function index() {
        $obj = Paises::where('pa_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->pa_nombre)) {
            if (!Paises::where('pa_nombre', $req->pa_nombre)->exists()) {
                $obj = new Paises;
                $obj->pa_nombre = $req->pa_nombre;
                if (isset($req->pa_estado))     { $obj->pa_estado   = $req->pa_estado; }
                if (isset($req->created_by))    { $obj->created_by  = $req->created_by; }
                $obj->updated_at = null;
                $obj->save();
                return response()->json($obj, 201);
            }
            else {
                return response()->make(['message' => 'Ya existe'], 409);
            }
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }

    public function read ($id) {
        $obj = ($id === 'all') ? Paises::all() : Paises::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Paises::where('pa_codigo', $req->pa_codigo)->exists()) {
            $obj = Paises::find($req->pa_codigo);
            $obj->pa_nombre = is_null($req->pa_nombre) ? $obj->pa_nombre : $req->pa_nombre;
            if (isset($req->pa_estado))     { $obj->pa_estado   = $req->pa_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->pa_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->pa_codigo)?400:404));
        }
    }
}
