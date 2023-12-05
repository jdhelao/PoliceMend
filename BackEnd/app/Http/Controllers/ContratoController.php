<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contratos;
use App\Models\ContratoTipos;
use DB;

class ContratoController extends Controller
{
    public function index() {
        $obj = Contratos::select([DB::raw("contratos.*, contrato_tipos.kt_nombre, concat(k1.pe_nombre1, ' ', k1.pe_apellido1) as kt_contratante_nombre, concat(k2.pe_nombre1, ' ', k2.pe_apellido1) as kt_contratista_nombre")])
        ->leftJoin('personas as k1', 'k1.pe_codigo', '=', 'contratos.kt_contratante')
        ->leftJoin('personas as k2', 'k2.pe_codigo', '=', 'contratos.kt_contratista')
        ->leftJoin('contrato_tipos', 'contrato_tipos.kt_codigo', '=', 'contratos.kt_codigo')
        ->where('ko_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function tipos($id = '') {
        $obj = ContratoTipos::where('kt_estado',true)->where('kt_codigo','like',(strlen($id)?$id:'%%'))->get();
        return response()->json($obj, 200);
    }

    public function porTipos($id = '') {
        $obj = Contratos::select([DB::raw("contratos.*, contrato_tipos.kt_nombre, concat(k1.pe_nombre1, ' ', k1.pe_apellido1) as kt_contratante_nombre, concat(k2.pe_nombre1, ' ', k2.pe_apellido1) as kt_contratista_nombre")])
        ->leftJoin('personas as k1', 'k1.pe_codigo', '=', 'contratos.kt_contratante')
        ->leftJoin('personas as k2', 'k2.pe_codigo', '=', 'contratos.kt_contratista')
        ->leftJoin('contrato_tipos', 'contrato_tipos.kt_codigo', '=', 'contratos.kt_codigo')
        ->where('ko_estado',true)->where('contratos.kt_codigo',$id)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (is_null($req->ko_codigo)) {
            if (!Contratos::where('ko_documento', $req->ko_documento)->exists()) {
                $obj = $this->save(new Contratos, $req);
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
        $obj = ($id === 'all')
        ? Contratos::select([DB::raw("contratos.*, contrato_tipos.kt_nombre, concat(k1.pe_nombre1, ' ', k1.pe_apellido1) as kt_contratante_nombre, concat(k2.pe_nombre1, ' ', k2.pe_apellido1) as kt_contratista_nombre")])
        ->leftJoin('personas as k1', 'k1.pe_codigo', '=', 'contratos.kt_contratante')
        ->leftJoin('personas as k2', 'k2.pe_codigo', '=', 'contratos.kt_contratista')
        ->leftJoin('contrato_tipos', 'contrato_tipos.kt_codigo', '=', 'contratos.kt_codigo')->get()
        : Contratos::select([DB::raw("contratos.*, contrato_tipos.kt_nombre, concat(k1.pe_nombre1, ' ', k1.pe_apellido1) as kt_contratante_nombre, concat(k2.pe_nombre1, ' ', k2.pe_apellido1) as kt_contratista_nombre")])
        ->leftJoin('personas as k1', 'k1.pe_codigo', '=', 'contratos.kt_contratante')
        ->leftJoin('personas as k2', 'k2.pe_codigo', '=', 'contratos.kt_contratista')
        ->leftJoin('contrato_tipos', 'contrato_tipos.kt_codigo', '=', 'contratos.kt_codigo')->find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Contratos::where('ko_codigo', $req->ko_codigo)->exists()) {
            $obj = Contratos::find($req->ko_codigo);
            $obj = $this->save($obj, $req);
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->ko_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->ko_codigo)?400:404));
        }
    }

    public function save (Contratos $obj , Request $req) {
        if (isset($req->ko_documento))      { $obj->ko_documento  = $req->ko_documento; }
        if (isset($req->kt_codigo))         { $obj->kt_codigo  = $req->kt_codigo; }
        if (isset($req->ko_inicio))         { $obj->ko_inicio  = $req->ko_inicio; }
        if (isset($req->ko_fin))            { $obj->ko_fin  = $req->ko_fin; }
        if (isset($req->ko_monto))          { $obj->ko_monto  = $req->ko_monto; }
        if (isset($req->ko_compadecientes)) { $obj->ko_compadecientes  = $req->ko_compadecientes; }
        if (isset($req->ko_clausulas))      { $obj->ko_clausulas  = $req->ko_clausulas; }
        if (isset($req->kt_contratante))    { $obj->kt_contratante  = $req->kt_contratante; }
        if (isset($req->kt_contratista))    { $obj->kt_contratista  = $req->kt_contratista; }
        if (isset($req->ko_estado))         { $obj->ko_estado  = $req->ko_estado; }

        /*existing data*/
        if (isset($obj->ko_codigo) and (isset($req->updated_by) or isset($req->us_codigo))) { $obj->updated_by  = (isset($req->updated_by)?$req->updated_by:$req->us_codigo); }
        /*new data*/
        if (is_null($obj->ko_codigo)) {
            if (isset($req->created_by) or isset($req->us_codigo)) { $obj->created_by  = (isset($req->created_by)?$req->created_by:$req->us_codigo); }
            $obj->updated_at = null;
        }
        $obj->save();
        return $obj;
    }

}
