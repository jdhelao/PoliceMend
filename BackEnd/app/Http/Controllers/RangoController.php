<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rangos;

class RangoController extends Controller
{
    public function index() {
        $obj = Rangos::where('ra_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->ra_nombre)) {
            if (!Rangos::where('ra_nombre', $req->ra_nombre)->exists()) {
                $obj = new Rangos;
                $obj->ra_nombre = $req->ra_nombre;
                if (isset($req->ra_estado))     { $obj->ra_estado   = $req->ra_estado; }
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
        $obj = ($id === 'all') ? Rangos::all() : Rangos::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Rangos::where('ra_codigo', $req->ra_codigo)->exists()) {
            $obj = Rangos::find($req->ra_codigo);
            $obj->ra_nombre = is_null($req->ra_nombre) ? $obj->ra_nombre : $req->ra_nombre;
            if (isset($req->ra_estado))     { $obj->ra_estado   = $req->ra_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->ra_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->ra_codigo)?400:404));
        }
    }
}
