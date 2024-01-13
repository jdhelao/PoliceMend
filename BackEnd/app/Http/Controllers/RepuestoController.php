<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repuestos;

class RepuestoController extends Controller
{
    public function index() {
        $obj = Repuestos::where('re_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->re_nombre)) {
            if (!Repuestos::where('re_nombre', $req->re_nombre)->exists()) {
                $obj = new Repuestos;
                $obj->re_nombre = $req->re_nombre;
                if (isset($req->re_estado))     { $obj->re_estado   = $req->re_estado; }
                if (isset($req->created_by))    { $obj->created_by  = $req->created_by??$req->us_codigo??null; }
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
        $obj = ($id === 'all') ? Repuestos::all() : Repuestos::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Repuestos::where('re_codigo', $req->re_codigo)->exists()) {
            $obj = Repuestos::find($req->re_codigo);
            $obj->re_nombre = is_null($req->re_nombre) ? $obj->re_nombre : $req->re_nombre;
            if (isset($req->re_estado))     { $obj->re_estado   = $req->re_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->re_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->re_codigo)?400:404));
        }
    }
}
