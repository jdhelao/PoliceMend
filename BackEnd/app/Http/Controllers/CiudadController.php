<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudades;
use DB;

class CiudadController extends Controller
{
    public function index($provincia='') {
        $obj = Ciudades::where('ci_estado',true)->where('ciudades.pr_codigo','like',(strlen($provincia)?$provincia:'%%'))
        ->select([DB::raw("ciudades.*, provincias.pa_codigo")])
        ->join('provincias', 'provincias.pr_codigo', '=', 'ciudades.pr_codigo')
        ->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->pr_codigo) and isset($req->ci_nombre)) {
            if (!Ciudades::where('ci_nombre', $req->ci_nombre)->where('pr_codigo', $req->pr_codigo)->exists()) {
                $obj = new Ciudades;
                $obj->pr_codigo = $req->pr_codigo;
                $obj->ci_nombre = $req->ci_nombre;
                if (isset($req->ci_estado))     { $obj->ci_estado   = $req->ci_estado; }
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
        $obj = ($id === 'all') ? Ciudades::select([DB::raw("ciudades.*, provincias.pa_codigo")])->join('provincias', 'provincias.pr_codigo', '=', 'ciudades.pr_codigo')->get() : Ciudades::select([DB::raw("ciudades.*, provincias.pa_codigo")])->join('provincias', 'provincias.pr_codigo', '=', 'ciudades.pr_codigo')->find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Ciudades::where('ci_codigo', $req->ci_codigo)->exists()) {
            $obj = Ciudades::find($req->ci_codigo);
            $obj->pr_codigo = is_null($req->pr_codigo) ? $obj->pr_codigo : $req->pr_codigo;
            $obj->ci_nombre = is_null($req->ci_nombre) ? $obj->ci_nombre : $req->ci_nombre;
            if (isset($req->ci_estado))     { $obj->ci_estado   = $req->ci_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->ci_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->ci_codigo)?400:404));
        }
    }
}
