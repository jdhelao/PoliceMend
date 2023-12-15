<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use DB;

class SolicitudVehiculoController extends Controller
{
    public function index() {
        $obj = SolicitudVehiculos::select()->where('sv_estado',true)->get();
        return response()->json($obj, 200);
    }
    public function getVehicleRequestsFromPerson ($id) {
        $obj = Vehiculos::select([DB::raw("vehiculos.*, paises.pa_nombre, vehiculo_marcas.vm_nombre, vehiculo_tipos.vt_nombre")])
        ->leftJoin('paises', 'paises.pa_codigo', '=', 'vehiculos.pa_codigo')
        ->leftJoin('vehiculo_marcas', 'vehiculo_marcas.vm_codigo', '=', 'vehiculos.vm_codigo')
        ->leftJoin('vehiculo_tipos', 'vehiculo_tipos.vt_codigo', '=', 'vehiculos.vt_codigo')
        ->join('vehiculo_custodios as vc', function ($join) use ($id){
            $join->on('vc.ve_codigo', '=', 'vehiculos.ve_codigo')
                 ->on('vc.pe_codigo', '=', DB::raw($id))
                 ->on('vc.vc_estado', 'vehiculos.ve_estado');
        })
        ->where('vehiculos.ve_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->kt_codigo) and isset($req->pe_codigo) and isset($req->ve_codigo)/* and isset($req->sv_descripcion)*/) {
            //if (!Entidades::where('vt_codigo', $req->vt_codigo)->where('en_placa', $req->en_placa)->orWhere('vt_codigo', $req->vt_codigo)->where('en_placa', $req->en_chasis)->exists()) {
                $obj = $this->save(new SolicitudVehiculos, $req);
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

        /*existing data*/
        if (isset($obj->sv_codigo) and (isset($req->updated_by) or isset($req->us_codigo))) { $obj->updated_by  = (isset($req->updated_by)?$req->updated_by:$req->us_codigo); }
        /*new data*/
        if (is_null($obj->sv_codigo)) {
            if (isset($req->created_by) or isset($req->us_codigo)) { $obj->created_by  = (isset($req->created_by)?$req->created_by:$req->us_codigo); }
            $obj->updated_at = null;
        }
        $obj->save();
        return $obj;
    }

    public function read ($id) {
        //$obj = ($id === 'all') ? Vehiculos::all() : Vehiculos::find($id);
        $obj = ($id === 'all')
        ? Vehiculos::select([DB::raw("vehiculos.*, paises.pa_nombre, vehiculo_marcas.vm_nombre, vehiculo_tipos.vt_nombre")])
        ->leftJoin('paises', 'paises.pa_codigo', '=', 'vehiculos.pa_codigo')
        ->leftJoin('vehiculo_marcas', 'vehiculo_marcas.vm_codigo', '=', 'vehiculos.vm_codigo')
        ->leftJoin('vehiculo_tipos', 'vehiculo_tipos.vt_codigo', '=', 'vehiculos.vt_codigo')->get()
        : Vehiculos::select([DB::raw("vehiculos.*, paises.pa_nombre, vehiculo_marcas.vm_nombre, vehiculo_tipos.vt_nombre")])
        ->leftJoin('paises', 'paises.pa_codigo', '=', 'vehiculos.pa_codigo')
        ->leftJoin('vehiculo_marcas', 'vehiculo_marcas.vm_codigo', '=', 'vehiculos.vm_codigo')
        ->leftJoin('vehiculo_tipos', 'vehiculo_tipos.vt_codigo', '=', 'vehiculos.vt_codigo')->find($id);
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
