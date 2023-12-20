<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use App\Models\VehiculoHistoriales;
use App\Models\Vehiculos;
use DB;

class SolicitudVehiculoController extends Controller
{
    public function index() {
        $obj = SolicitudVehiculos::select([DB::raw("solicitud_vehiculos.*, kt.kt_nombre, ve.ve_placa, ve.ve_color, ve.ve_combustible, pa.pa_nombre, vm.vm_nombre, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres")])
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'solicitud_vehiculos.ve_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'solicitud_vehiculos.pe_codigo')
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'solicitud_vehiculos.kt_codigo')
        ->leftJoin('paises as pa', 'pa.pa_codigo', '=', 've.pa_codigo')
        ->leftJoin('vehiculo_marcas as vm', 'vm.vm_codigo', '=', 've.vm_codigo')
        ->leftJoin('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->where('sv_estado',true)->get();
        return response()->json($obj, 200);
    }
    public function getVehicleRequestsFromPerson ($id) {
        $obj = SolicitudVehiculos::select([DB::raw("solicitud_vehiculos.*, kt.kt_nombre, ve.ve_placa, ve.ve_color, ve.ve_combustible, pa.pa_nombre, vm.vm_nombre, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres")])
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'solicitud_vehiculos.ve_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'solicitud_vehiculos.pe_codigo')
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'solicitud_vehiculos.kt_codigo')
        ->leftJoin('paises as pa', 'pa.pa_codigo', '=', 've.pa_codigo')
        ->leftJoin('vehiculo_marcas as vm', 'vm.vm_codigo', '=', 've.vm_codigo')
        ->leftJoin('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->where('sv_estado',true)->where('solicitud_vehiculos.pe_codigo','like',($id=='all'?'%%':$id))->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->kt_codigo) and isset($req->pe_codigo) and isset($req->ve_codigo)/* and isset($req->sv_descripcion)*/) {
            //if (!Entidades::where('vt_codigo', $req->vt_codigo)->where('en_placa', $req->en_placa)->orWhere('vt_codigo', $req->vt_codigo)->where('en_placa', $req->en_chasis)->exists()) {
                $obj = $this->save(new SolicitudVehiculos, $req);
                /* Save Log */
                VehiculoHistoriales::create(['vh_tipo' => 'kms','ve_codigo' => $obj->ve_codigo,'vh_valor' => $obj->ve_km,               'created_by' => ($req->created_by??$req->us_codigo??null),]);
                VehiculoHistoriales::create(['vh_tipo' => 'gas','ve_codigo' => $obj->ve_codigo,'vh_valor' => $obj->ve_combustible_nivel,'created_by' => ($req->created_by??$req->us_codigo??null),]);
                Vehiculos::where('ve_codigo', $obj->ve_codigo)->update(['ve_km' => $obj->ve_km]);
                /* return request */
                return response()->json($obj, 201);
            /*}
            else {
                return response()->make(['message' => 'Ya existe'], 409);
            }*/
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }

    public function save (SolicitudVehiculos $obj , Request $req) {
        if (isset($req->kt_codigo))             { $obj->kt_codigo  = $req->kt_codigo; }
        if (isset($req->pe_codigo))             { $obj->pe_codigo  = $req->pe_codigo; }
        if (isset($req->ve_codigo))             { $obj->ve_codigo  = $req->ve_codigo; }
        if (isset($req->ve_km))                 { $obj->ve_km  = $req->ve_km; }
        if (isset($req->ve_combustible_nivel))  { $obj->ve_combustible_nivel  = $req->ve_combustible_nivel; }
        if (isset($req->sv_fecha_requerimiento)){ $obj->sv_fecha_requerimiento  = $req->sv_fecha_requerimiento; }
        if (isset($req->sv_descripcion))        { $obj->sv_descripcion  = $req->sv_descripcion; }
        if (isset($req->sv_aprobacion))         { $obj->sv_aprobacion  = $req->sv_aprobacion; }

        if (is_null($obj->sv_codigo)) { /*new data*/
            $obj->created_by = ($req->created_by??$req->us_codigo??null);
            $obj->updated_at = null;
        }
        else { /*existing data*/
            $obj->updated_by  = ($req->updated_by??$req->us_codigo??null);
        }
        $obj->save();
        return $obj;
    }

    public function read ($id) {
        $obj = ($id === 'all')
        ? SolicitudVehiculos::select([DB::raw("solicitud_vehiculos.*, kt.kt_nombre, ve.ve_placa, ve.ve_color, ve.ve_combustible, pa.pa_nombre, vm.vm_nombre, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres")])
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'solicitud_vehiculos.ve_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'solicitud_vehiculos.pe_codigo')
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'solicitud_vehiculos.kt_codigo')
        ->leftJoin('paises as pa', 'pa.pa_codigo', '=', 've.pa_codigo')
        ->leftJoin('vehiculo_marcas as vm', 'vm.vm_codigo', '=', 've.vm_codigo')
        ->leftJoin('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->where('sv_estado',true)->get()
        : SolicitudVehiculos::select([DB::raw("solicitud_vehiculos.*, kt.kt_nombre, ve.ve_placa, ve.ve_color, ve.ve_combustible, pa.pa_nombre, vm.vm_nombre, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres")])
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'solicitud_vehiculos.ve_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'solicitud_vehiculos.pe_codigo')
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'solicitud_vehiculos.kt_codigo')
        ->leftJoin('paises as pa', 'pa.pa_codigo', '=', 've.pa_codigo')
        ->leftJoin('vehiculo_marcas as vm', 'vm.vm_codigo', '=', 've.vm_codigo')
        ->leftJoin('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->where('sv_estado',true)->get()->find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (SolicitudVehiculos::where('sv_codigo', $req->sv_codigo)->exists()) {
            $obj = SolicitudVehiculos::find($req->sv_codigo);
            $obj = $this->save($obj, $req);
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->sv_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->sv_codigo)?400:404));
        }
    }
}
