<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use App\Models\VehiculoHistoriales;
use App\Models\Vehiculos;
use App\Models\OrdenMovilizaciones;
use DB;
use Carbon\Carbon;

class OrdenMovilizacionController extends Controller
{
    public function getOrderByPerson ($id, $person) {
        $obj = OrdenMovilizaciones::select([DB::raw("orden_movilizaciones.*, sv.*")])
        ->join('solicitud_vehiculos as sv', 'sv.sv_codigo', '=', 'orden_movilizaciones.sv_codigo')
        ->where('od_estado',true)
        ->where('pe_codigo',$person)
        ->find($id);
        return response()->json($obj, 200);
    }
    
    public function getReport ($ini, $end) {
        $obj = OrdenMovilizaciones::select([DB::raw("orden_movilizaciones.*, ve.*, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres, cast(IFNULL(orden_movilizaciones.updated_at,orden_movilizaciones.created_at) as date) as od_fecha_salida, cast(IFNULL(orden_movilizaciones.updated_at,orden_movilizaciones.created_at) as time) as od_hora_salida, sv.sv_descripcion, di.*")])
        ->join('solicitud_vehiculos as sv', 'sv.sv_codigo', '=', 'orden_movilizaciones.sv_codigo')
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'sv.ve_codigo')
        ->join('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'sv.pe_codigo')
        ->join('vehiculo_subcircuitos as vs', 'vs.ve_codigo', '=', 've.ve_codigo')
        ->join('subcircuitos as sc', 'sc.sc_codigo', '=', 'vs.sc_codigo')
        ->join('circuitos as cc', 'cc.cc_codigo', '=', 'sc.cc_codigo')
        ->join('distritos as di', 'di.di_codigo', '=', 'dd.di_codigo')
        ->where('od_estado',true)
        ->where('vs_estado',true)
        ->where('sc_estado',true)
        ->where('cc_estado',true)
        ->where('di_estado',true)
        ->whereBetween(DB::raw('IFNULL(orden_movilizaciones.updated_at, orden_movilizaciones.created_at)'), [$ini, Carbon::parse($end)->endOfDay()->toDateTimeString()])->get();
        return response()->json($obj, 200);
    }

    public function save (OrdenMovilizaciones $obj , Request $req) {
        if (isset($req->sv_codigo))             { $obj->sv_codigo  = $req->sv_codigo; }
        if (isset($req->od_ocupantes))          { $obj->od_ocupantes  = $req->od_ocupantes; }
        if (isset($req->od_estado))             { $obj->od_estado  = $req->od_estado; }

        if (is_null($obj->od_codigo)) {
            /*new data*/
            $obj->created_by = ($req->created_by??$req->us_codigo??null);
            $obj->updated_at = null;
        }
        else {
            /*existing data*/
            $obj->updated_by  = ($req->updated_by??$req->us_codigo??null);
        }
        $obj->save();
        return $obj;
    }

    public function update (Request $req) {
        if (OrdenMovilizaciones::find($req->od_codigo)->exists()) {
            $obj = OrdenMovilizaciones::find($req->od_codigo);
            $obj = $this->save($obj, $req);
            /* Save Log */
            // VehiculoHistoriales::create(['vh_tipo' => 'kms','ve_codigo' => $obj->ve_codigo,'vh_valor' => $obj->ve_km,               'created_by' => ($req->created_by??$req->us_codigo??null),]);
            // VehiculoHistoriales::create(['vh_tipo' => 'gas','ve_codigo' => $obj->ve_codigo,'vh_valor' => $obj->ve_combustible_nivel,'created_by' => ($req->created_by??$req->us_codigo??null),]);
            //////////Vehiculos::where('ve_codigo', $req->ve_codigo)->update(['ve_km' => $obj->od_km]);
            /* return request */
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->od_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->od_codigo)?400:404));
        }
    }
}
