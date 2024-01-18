<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogos;
use DB;

class CatalogoController extends Controller
{
    public function index() {
        $obj = Catalogos::where('ca_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->di_codigo) and isset($req->ca_nombre)) {
            if (!Catalogos::where('ca_nombre', $req->ca_nombre)->exists()) {
                $obj = new Catalogos;
                $obj->ca_codigo = Catalogos::select(DB::raw("concat('".$req->di_codigo."', 'C',RIGHT(concat('00', IFNULL(max(cast(RIGHT(ca_codigo,2) as UNSIGNED)),0)+1),2)) as ca_codigo"))->where('di_codigo', '=', $req->di_codigo)->first()->ca_codigo;
                $obj->di_codigo = $req->di_codigo;
                $obj->ca_nombre = $req->ca_nombre;
                if (isset($req->ca_estado))     { $obj->ca_estado   = $req->ca_estado; }
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

    public function read ($type, $id) {
        $cat = Catalogos::find($type);
        //$obj = ($id === 'all') ? Catalogos::all() : Catalogos::find($id);
        $obj = DB::table($cat->ca_tabla)
        ->select(DB::raw($cat->ca_prefijo.'codigo as ca_codigo, '.$cat->ca_prefijo.'nombre as ca_nombre, '. $cat->ca_prefijo.'estado as ca_estado'))
        //->where($cat->ca_prefijo.'estado', 'like', ($id === 'all'?'%%':1))
        ->where($cat->ca_prefijo.'codigo', 'like', ($id === 'all'?'%%':$id));
        //->get();
        if ($id === 'all') {
            $obj = $obj->get();
        }
        else {
            $obj = $obj->first();
        }
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Catalogos::where('ca_codigo', $req->ca_codigo)->exists()) {
            $obj = Catalogos::find($req->ca_codigo);
            $obj->di_codigo = is_null($req->di_codigo) ? $obj->di_codigo : $req->di_codigo;
            $obj->ca_nombre = is_null($req->ca_nombre) ? $obj->ca_nombre : $req->ca_nombre;
            if (isset($req->ca_estado))     { $obj->ca_estado   = $req->ca_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->ca_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->ca_codigo)?400:404));
        }
    }
}
