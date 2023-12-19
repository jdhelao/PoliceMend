<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfiles;

class PerfilController extends Controller
{
    public function index() {
        $perfiles = Perfiles::where('pf_estado',true)->get();
        return response()->json($perfiles, 200);
    }

    public function create (Request $req) {
        if (isset($req->pf_nombre)) {
            if (!Perfiles::where('pf_nombre', $req->pf_nombre)->exists()) {
                $obj = $this->save(new Perfiles, $req);
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

    public function save (Perfiles $obj , Request $req) {
        if (isset($req->pf_nombre))             { $obj->pf_nombre  = $req->pf_nombre; }
        if (isset($req->pf_estado))             { $obj->pf_estado  = $req->pf_estado; }

        /*existing data*/
        if (isset($obj->pf_codigo) and (isset($req->updated_by) or isset($req->us_codigo))) { $obj->updated_by  = (isset($req->updated_by)?$req->updated_by:$req->us_codigo); }
        /*new data*/
        if (is_null($obj->pf_codigo)) {
            if (isset($req->created_by) or isset($req->us_codigo)) { $obj->created_by  = (isset($req->created_by)?$req->created_by:$req->us_codigo); }
            $obj->updated_at = null;
        }
        $obj->save();
        return $obj;
    }

    public function read ($id) {
        /*$perfil = ($id === 'all') ? Perfiles::all() : Perfiles::find($id)->get();
        if (!empty($perfil)) {
            return response()->json($perfil, 200);
        }
        else {
            return response()->json([], 404);
        }
        */
        $obj = ($id === 'all') ? Perfiles::all() : Perfiles::find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Perfiles::where('pf_codigo', $req->pf_codigo)->exists()) {
            $obj = Perfiles::find($req->pf_codigo);
            $obj = $this->save($obj, $req);
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->en_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->pf_codigo)?400:404));
        }
    }
}
