<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincias;
use DB;

class ProvinciaController extends Controller
{
    public function index($pais = '') {
        $obj = Provincias::where('pr_estado',true)->where('pa_codigo','like',(strlen($pais)?$pais:'%%'))
        ->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->pa_codigo) and isset($req->pr_nombre)) {
            if (!Provincias::where('pr_nombre', $req->pr_nombre)->where('pa_codigo', $req->pa_codigo)->exists()) {
                $obj = new Provincias;
                $obj->pa_codigo = $req->pa_codigo;
                $obj->pr_nombre = $req->pr_nombre;
                if (isset($req->pr_estado))     { $obj->pr_estado   = $req->pr_estado; }
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
        $obj = ($id === 'all') ? Provincias::all() : Provincias::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Provincias::where('pr_codigo', $req->pr_codigo)->exists()) {
            $obj = Provincias::find($req->pr_codigo);
            $obj->pa_codigo = is_null($req->pa_codigo) ? $obj->pa_codigo : $req->pa_codigo;
            $obj->pr_nombre = is_null($req->pr_nombre) ? $obj->pr_nombre : $req->pr_nombre;
            if (isset($req->pr_estado))     { $obj->pr_estado   = $req->pr_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->pr_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->pr_codigo)?400:404));
        }
    }
}
