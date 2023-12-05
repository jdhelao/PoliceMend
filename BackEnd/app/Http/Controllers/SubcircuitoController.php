<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcircuitos;
use DB;

class SubcircuitoController extends Controller
{
    public function index($circuit='') {
        $obj = Subcircuitos::where('sc_estado',true)->where('cc_codigo','like',(strlen($circuit)?$circuit:'%%'))
        ->select([DB::raw("*, left(sc_codigo,5) as di_codigo")])
        ->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->cc_codigo) and isset($req->sc_nombre)) {
            if (!Subcircuitos::where('sc_nombre', $req->sc_nombre)->exists()) {
                $obj = new Subcircuitos;
                $obj->sc_codigo = Subcircuitos::select(DB::raw("concat('".$req->cc_codigo."', 'S',RIGHT(concat('00', IFNULL(max(cast(RIGHT(sc_codigo,2) as UNSIGNED)),0)+1),2)) as sc_codigo"))->where('cc_codigo', '=', $req->cc_codigo)->first()->sc_codigo;
                $obj->cc_codigo = $req->cc_codigo;
                $obj->sc_nombre = $req->sc_nombre;
                if (isset($req->sc_estado))     { $obj->sc_estado   = $req->sc_estado; }
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
        $obj = ($id === 'all') ? Subcircuitos::select([DB::raw("*, left(sc_codigo,5) as di_codigo")])->get() : Subcircuitos::select([DB::raw("*, left(sc_codigo,5) as di_codigo")])->find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Subcircuitos::where('sc_codigo', $req->sc_codigo)->exists()) {
            $obj = Subcircuitos::find($req->sc_codigo);
            $obj->cc_codigo = is_null($req->cc_codigo) ? $obj->cc_codigo : $req->cc_codigo;
            $obj->sc_nombre = is_null($req->sc_nombre) ? $obj->sc_nombre : $req->sc_nombre;
            if (isset($req->sc_estado))     { $obj->sc_estado   = $req->sc_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->sc_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->sc_codigo)?400:404));
        }
    }
}
