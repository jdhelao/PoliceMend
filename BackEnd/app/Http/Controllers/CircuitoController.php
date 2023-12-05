<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Circuitos;
use DB;

class CircuitoController extends Controller
{
    public function index($distrito = '') {
        $obj = Circuitos::where('cc_estado',true)->where('di_codigo','like',(strlen($distrito)?$distrito:'%%'))
        ->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->di_codigo) and isset($req->cc_nombre)) {
            if (!Circuitos::where('cc_nombre', $req->cc_nombre)->exists()) {
                $obj = new Circuitos;
                $obj->cc_codigo = Circuitos::select(DB::raw("concat('".$req->di_codigo."', 'C',RIGHT(concat('00', IFNULL(max(cast(RIGHT(cc_codigo,2) as UNSIGNED)),0)+1),2)) as cc_codigo"))->where('di_codigo', '=', $req->di_codigo)->first()->cc_codigo;
                $obj->di_codigo = $req->di_codigo;
                $obj->cc_nombre = $req->cc_nombre;
                if (isset($req->cc_estado))     { $obj->cc_estado   = $req->cc_estado; }
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
        $obj = ($id === 'all') ? Circuitos::all() : Circuitos::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Circuitos::where('cc_codigo', $req->cc_codigo)->exists()) {
            $obj = Circuitos::find($req->cc_codigo);
            $obj->di_codigo = is_null($req->di_codigo) ? $obj->di_codigo : $req->di_codigo;
            $obj->cc_nombre = is_null($req->cc_nombre) ? $obj->cc_nombre : $req->cc_nombre;
            if (isset($req->cc_estado))     { $obj->cc_estado   = $req->cc_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->cc_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->cc_codigo)?400:404));
        }
    }
}
