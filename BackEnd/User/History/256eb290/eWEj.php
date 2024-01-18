<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use App\Models\VehiculoHistoriales;
use App\Models\Vehiculos;
use App\Models\OrdenMantenimientos;
use DB;
use Carbon\Carbon;

class OrdenMantenimientoController extends Controller
{
    public function getOrderByPerson ($id, $person) {
        $obj = OrdenMantenimientos::select([DB::raw("orden_mantenimientos.*, sv.*")])
        ->join('solicitud_vehiculos as sv', 'sv.sv_codigo', '=', 'orden_mantenimientos.sv_codigo')

        ->leftJoin('entidad_usuarios as eu', 'eu.en_codigo', '=', 'orden_mantenimientos.en_codigo')
        ->leftJoin('usuarios as us', 'us.us_codigo', '=', 'eu.us_codigo')
        
        ->where('om_estado',true)
        //->where('sv.pe_codigo',$person)->orWhere('us.pe_codigo', $person)
        ->whereRaw($person.' IN (sv.pe_codigo, us.pe_codigo, 1/*admin*/)')
        ->find($id);
        return response()->json($obj, 200);
    }

    public function getReport ($ini, $end, $fin) {
        $obj = OrdenMantenimientos::select([DB::raw("orden_mantenimientos.*, ve.*, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres")])
        ->join('solicitud_vehiculos as sv', 'sv.sv_codigo', '=', 'orden_mantenimientos.sv_codigo')
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'sv.ve_codigo')
        ->join('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'sv.pe_codigo')
        ->where('om_estado',true)
        ->where(DB::raw('CAST(om_progreso AS UNSIGNED)'), '>=', ($fin?100:0))
        ->whereBetween(DB::raw('IFNULL(orden_mantenimientos.updated_at, orden_mantenimientos.created_at)'), [$ini, Carbon::parse($end)->endOfDay()->toDateTimeString()])->get();
        return response()->json($obj, 200);
    }

    public function save (OrdenMantenimientos $obj , Request $req) {
        if (isset($req->sv_codigo))             { $obj->sv_codigo  = $req->sv_codigo; }
        if (isset($req->en_codigo))             { $obj->en_codigo  = $req->en_codigo; }
        if (isset($req->om_total))              { $obj->om_total  = $req->om_total; }
        if (isset($req->pe_codigo))             { $obj->pe_codigo  = $req->pe_codigo; }
        if (isset($req->om_ingreso_aceptacion)) { $obj->om_ingreso_aceptacion  = $req->om_ingreso_aceptacion; }
        if (isset($req->om_ingreso_condicion))  { $obj->om_ingreso_condicion  = $req->om_ingreso_condicion; }
        if (isset($req->om_entrega_aceptacion)) { $obj->om_entrega_aceptacion  = $req->om_entrega_aceptacion; }
        if (isset($req->om_entrega_condicion))  { $obj->om_entrega_condicion  = $req->om_entrega_condicion; }
        if (isset($req->om_progreso))           { $obj->om_progreso  = $req->om_progreso; }
        if (isset($req->om_documento))          { $obj->om_documento  = $req->om_documento; }
        if (isset($req->om_archivo_datos))      { $obj->om_archivo_datos  = $req->om_archivo_datos; }
        if (isset($req->om_archivo_tipo))       { $obj->om_archivo_tipo  = $req->om_archivo_tipo; }
        if (isset($req->om_estado))             { $obj->om_estado  = $req->om_estado; }

        if (is_null($obj->om_codigo)) { /*new data*/
            $obj->created_by = ($req->created_by??$req->us_codigo??null);
            $obj->updated_at = null;
        }
        else { /*existing data*/
            $obj->updated_by  = ($req->updated_by??$req->us_codigo??null);
        }
        $obj->save();
        return $obj;
    }

    public function update (Request $req) {
        if (OrdenMantenimientos::find($req->om_codigo)->exists()) {
            $obj = OrdenMantenimientos::find($req->om_codigo);
            $obj = $this->save($obj, $req);
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->om_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->om_codigo)?400:404));
        }
    }
}
