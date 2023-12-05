<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distritos;
use DB;

class DistritoController extends Controller
{
    public function index() {
        $obj = Distritos::where('di_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->di_nombre)) {
            if (!Distritos::where('di_nombre', $req->di_nombre)->exists()) {
                $obj = new Distritos;
                $obj->di_codigo = Distritos::select(DB::raw("concat('11D',RIGHT(concat('00', IFNULL(max(cast(RIGHT(di_codigo,2) as UNSIGNED)),0)+1),2)) as di_codigo"))->first()->di_codigo;
                $obj->di_nombre = $req->di_nombre;
                if (isset($req->di_estado))     { $obj->di_estado   = $req->di_estado; }
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
        $obj = ($id === 'all') ? Distritos::all() : Distritos::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Distritos::where('di_codigo', $req->di_codigo)->exists()) {
            $obj = Distritos::find($req->di_codigo);
            $obj->di_nombre = is_null($req->di_nombre) ? $obj->di_nombre : $req->di_nombre;
            if (isset($req->di_estado))     { $obj->di_estado   = $req->di_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->di_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->di_codigo)?400:404));
        }
    }

    public function test () {
        $obj = Distritos::select(DB::raw("concat('11D',RIGHT(concat('00', IFNULL(max(cast(RIGHT(di_codigo,2) as UNSIGNED)),0)+1),2)) as di_codigo"))->first()->di_codigo;
        return response()->json($obj,200);
    }
}
