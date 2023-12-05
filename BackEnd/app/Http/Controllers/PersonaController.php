<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personas;
use DB;

class PersonaController extends Controller
{
    public function index() {
        $obj = Personas::select([DB::raw("personas.*, provincias.pr_codigo, provincias.pa_codigo")])->leftJoin('ciudades', 'ciudades.ci_codigo', '=', 'personas.ci_codigo')->leftJoin('provincias', 'provincias.pr_codigo', '=', 'ciudades.pr_codigo')->where('pe_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->pe_dni)) {
            if (!Personas::where('pe_dni', $req->pe_dni)->exists()) {
                $obj = new Personas;
                $obj->pe_dni = $req->pe_dni;

                if (isset($req->pe_nombre1))    { $obj->pe_nombre1  = $req->pe_nombre1; }
                if (isset($req->pe_nombre2))    { $obj->pe_nombre2  = $req->pe_nombre2; }
                if (isset($req->pe_apellido1))  { $obj->pe_apellido1= $req->pe_apellido1; }
                if (isset($req->pe_apellido2))  { $obj->pe_apellido2= $req->pe_apellido2; }
                if (isset($req->pe_sangre))     { $obj->pe_sangre   = $req->pe_sangre; }
                if (isset($req->pe_fnacimiento)){ $obj->pe_fnacimiento   = $req->pe_fnacimiento; }
                if (isset($req->ci_codigo))     { $obj->ci_codigo   = $req->ci_codigo; }
                if (isset($req->ra_codigo))     { $obj->ra_codigo   = $req->ra_codigo; }

                if (isset($req->pe_estado))     { $obj->pe_estado   = $req->pe_estado; }
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
        //$obj = ($id === 'all') ? Personas::all() : Personas::find($id);
        $obj = ($id === 'all') ? Personas::select([DB::raw("personas.*, provincias.pr_codigo, provincias.pa_codigo")])->leftJoin('ciudades', 'ciudades.ci_codigo', '=', 'personas.ci_codigo')->leftJoin('provincias', 'provincias.pr_codigo', '=', 'ciudades.pr_codigo')->get() : Personas::select([DB::raw("personas.*, provincias.pr_codigo, provincias.pa_codigo")])->leftJoin('ciudades', 'ciudades.ci_codigo', '=', 'personas.ci_codigo')->leftJoin('provincias', 'provincias.pr_codigo', '=', 'ciudades.pr_codigo')->find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Personas::where('pe_codigo', $req->pe_codigo)->exists()) {
            $obj = Personas::find($req->pe_codigo);

            if (isset($req->pe_dni))        { $obj->pe_dni  = $req->pe_dni; }
            if (isset($req->pe_nombre1))    { $obj->pe_nombre1  = $req->pe_nombre1; }
            if (isset($req->pe_nombre2))    { $obj->pe_nombre2  = $req->pe_nombre2; }
            if (isset($req->pe_apellido1))  { $obj->pe_apellido1= $req->pe_apellido1; }
            if (isset($req->pe_apellido2))  { $obj->pe_apellido2= $req->pe_apellido2; }
            if (isset($req->pe_sangre))     { $obj->pe_sangre   = $req->pe_sangre; }
            if (isset($req->pe_fnacimiento)){ $obj->pe_fnacimiento   = $req->pe_fnacimiento; }
            if (isset($req->ci_codigo))     { $obj->ci_codigo   = $req->ci_codigo; }
            if (isset($req->ra_codigo))     { $obj->ra_codigo   = $req->ra_codigo; }

            if (isset($req->pe_estado))     { $obj->pe_estado   = $req->pe_estado; }
            if (isset($req->updated_by))    { $obj->updated_by  = $req->updated_by; }
            $obj->save();
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->pe_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->pe_codigo)?400:404));
        }
    }
}
