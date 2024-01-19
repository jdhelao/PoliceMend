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
        if ($type == 0) {
            return response()->json([], 404);
        }
        $cat = Catalogos::find($type);
        //$obj = ($id === 'all') ? Catalogos::all() : Catalogos::find($id);
        $obj = DB::table($cat->ca_tabla)
        ->select(DB::raw($cat->ca_prefijo.'codigo as ca_codigo, '.$cat->ca_prefijo.'nombre as ca_nombre, '. $cat->ca_prefijo.'estado as ca_estado'))
        //->where($cat->ca_prefijo.'estado', 'like', ($id === 'all'?'%%':1))
        ->where($cat->ca_prefijo.'codigo','>',0)
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

    public function save ($type, Request $req) {
        if (Catalogos::where('ca_codigo', $type)->exists()) {
            $cat = Catalogos::find($type);
            $id = null;
            //return response()->json([$req->ca_codigo], 202);
            if (isset($req->ca_codigo) and !is_null($req->ca_codigo)) {
                // update existing
                DB::table($cat->ca_tabla)->where($cat->ca_prefijo.'codigo',$req->ca_codigo)->update([$cat->ca_prefijo.'nombre' => (isset($req->ca_nombre)?$req->ca_nombre:$cat->ca_prefijo.'nombre'), $cat->ca_prefijo.'estado' => (isset($req->ca_estado)?$req->ca_estado:$cat->ca_prefijo.'estado'), 'updated_by' => ($req->us_codigo??null)]);
                $id = $req->ca_codigo;
            }
            else {
                // Create new
                // DB::insert('insert into '. $cat->ca_tabla .' ('. $cat->ca_prefijo .'nombre, '. $cat->ca_prefijo .'estado, created_by) values (?, ?, ?)', [$req->ca_nombre, true, ($req->us_codigo??null)]);
                $id = DB::table($cat->ca_tabla)->insertGetId([
                    $cat->ca_prefijo .'nombre' => $req->ca_nombre,
                    $cat->ca_prefijo .'estado' => true,
                    'created_by' => ($req->us_codigo??null)
                ]);
            }
            return $this->read($type, $id);
            //return response()->json($obj, 202);
        }
        else {
            return response()->make('Petición incorrecta', 400);
        }

    }
}
