<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Perfiles;
use DB;

class UsuarioController extends Controller
{
    public function index() {
        $usuarios = Usuarios::where('us_estado',true)->get();
        return response()->json($usuarios, 200);
    }

    public function create (Request $request) {
        if (isset($request->us_login)) {
            if (!Usuarios::where('us_login', $request->us_login)->exists()) {
                $usuario = new Usuarios;
                $usuario->us_login = $request->us_login;
                if (isset($request->pe_codigo))     { $usuario->pe_codigo   = $request->pe_codigo; }
                if (isset($request->pf_codigo))     { $usuario->pf_codigo   = $request->pf_codigo; }
                $usuario->us_password = hash('sha256',(hash('md5',$usuario->us_login).hash('sha256',   ((isset($request->us_password) and strlen($request->us_password))?$request->us_password:$usuario->us_password)    )));
                if (isset($request->us_estado))     { $usuario->us_estado   = $request->us_estado; }
                if (isset($request->created_by))    { $usuario->created_by  = $request->created_by; }
                $usuario->updated_at = null;
                $usuario->save();
                return response()->json($usuario, 201);
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
        //$usuario = ($id === 'all') ? Usuarios::all() : Usuarios::where('us_codigo',(int)$id)->get();
        $usuario = ($id === 'all') ? Usuarios::all() : Usuarios::find((int)$id);
        return response()->json($usuario, (strlen(serialize($usuario))>2?200:404));
    }

    public function update (Request $request) {
        if (Usuarios::where('us_codigo', $request->us_codigo)->exists()) {
            $usuario = Usuarios::find($request->us_codigo);
            if (isset($request->us_login) and strlen($request->us_login))      { $usuario->us_login    = $request->us_login; }
            if (isset($request->pe_codigo))     { $usuario->pe_codigo   = $request->pe_codigo; }
            if (isset($request->pf_codigo))     { $usuario->pf_codigo   = $request->pf_codigo; }
            $usuario->us_password = hash('sha256',(hash('md5',$usuario->us_login).hash('sha256',   ((isset($request->us_password) and strlen($request->us_password))?$request->us_password:$usuario->us_password)    )));
            if (isset($request->us_estado))     { $usuario->us_estado   = $request->us_estado; }
            if (isset($request->updated_by))    { $usuario->updated_by  = $request->updated_by; }
            $usuario->save();
            return response()->json($usuario, 202);
        }
        else {
            return response()->make((!isset($request->us_codigo)?'Petición incorrecta':'No encontrado'), (!isset($request->us_codigo)?400:404));
        }
    }

    public function login ($login, $password) {
        $usuario = Usuarios::where([['us_estado', '=', true],[DB::raw('MD5(us_login)'), '=', $login],['us_password', '=', hash('sha256',($login.$password))]])->get();
        if (sizeof($usuario) and !is_null($usuario)) {
            $usuario[0]->setAttribute('token', hash('sha256', $usuario[0]->us_codigo.$usuario[0]->us_login.$usuario[0]->us_password.$usuario[0]->us_estado));
            $perfil = Perfiles::find($usuario[0]->pf_codigo);
            if (isset($perfil->pf_nombre)) {
                $usuario[0]->setAttribute('pf_nombre', $perfil->pf_nombre);
            }
            /*
            unset($usuario[0]->us_password);
            unset($usuario[0]->created_at);
            unset($usuario[0]->created_by);
            unset($usuario[0]->updated_at);
            unset($usuario[0]->updated_by);
            */

            return response()->json($usuario[0], 202);
        }
        else {
            //return response()->make(null, (strlen($login)?401:400));
            return response()->make([
                'message' => strlen($login)?'Credenciales invalidas':'Petición incorrecta'
            ], strlen($login)?401:400);
        }
    }
}
