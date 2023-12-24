<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use App\Models\VehiculoHistoriales;
use App\Models\Vehiculos;
use App\Models\OrdenAbastecimientos;
use DB;
use Carbon\Carbon;

class OrdenAbastecimientoController extends Controller
{
    public function getOrderByPerson ($id, $person) {
        $obj = OrdenAbastecimientos::select([DB::raw("orden_abastecimientos.*, sv.*")])
        ->join('solicitud_vehiculos as sv', 'sv.sv_codigo', '=', 'orden_abastecimientos.sv_codigo')
        ->where('oa_estado',true)
        ->where('pe_codigo',$person)
        ->find($id);
        return response()->json($obj, 200);
    }
    
    public function getReport ($ini, $end) {
        $obj = OrdenAbastecimientos::select([DB::raw("orden_abastecimientos.*, ve.*, vt.vt_nombre, pe.pe_dni, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres")])
        ->join('solicitud_vehiculos as sv', 'sv.sv_codigo', '=', 'orden_abastecimientos.sv_codigo')
        ->join('vehiculos as ve', 've.ve_codigo', '=', 'sv.ve_codigo')
        ->join('vehiculo_tipos as vt', 'vt.vt_codigo', '=', 've.vt_codigo')
        ->join('personas as pe', 'pe.pe_codigo', '=', 'sv.pe_codigo')
        ->where('oa_estado',true)
        ->whereBetween(DB::raw('IFNULL(orden_abastecimientos.updated_at, orden_abastecimientos.created_at)'), [$ini, Carbon::parse($end)->endOfDay()->toDateTimeString()])->get();
        return response()->json($obj, 200);
    }

    public function save (OrdenAbastecimientos $obj , Request $req) {
        if (isset($req->sv_codigo))             { $obj->sv_codigo  = $req->sv_codigo; }
        if (isset($req->en_codigo))             { $obj->en_codigo  = $req->en_codigo; }
        if (isset($req->oa_total))              { $obj->oa_total  = $req->oa_total; }
        if (isset($req->oa_galones))            { $obj->oa_galones  = $req->oa_galones; }
        if (isset($req->oa_km))                 { $obj->oa_km  = $req->oa_km; }
        if (isset($req->oa_combustible_nivel))  { $obj->oa_combustible_nivel  = $req->oa_combustible_nivel; }
        if (isset($req->oa_documento))          { $obj->oa_documento  = $req->oa_documento; }
        if (isset($req->oa_archivo_datos))      { $obj->oa_archivo_datos  = $req->oa_archivo_datos; }
        if (isset($req->oa_archivo_tipo))       { $obj->oa_archivo_tipo  = $req->oa_archivo_tipo; }
        if (isset($req->oa_estado))             { $obj->oa_estado  = $req->oa_estado; }

        if (is_null($obj->oa_codigo)) { /*new data*/
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
        if (OrdenAbastecimientos::find($req->oa_codigo)->exists()) {
            $obj = OrdenAbastecimientos::find($req->oa_codigo);
            $obj = $this->save($obj, $req);
            /* Save Log */
            // VehiculoHistoriales::create(['vh_tipo' => 'kms','ve_codigo' => $obj->ve_codigo,'vh_valor' => $obj->ve_km,               'created_by' => ($req->created_by??$req->us_codigo??null),]);
            // VehiculoHistoriales::create(['vh_tipo' => 'gas','ve_codigo' => $obj->ve_codigo,'vh_valor' => $obj->ve_combustible_nivel,'created_by' => ($req->created_by??$req->us_codigo??null),]);
            Vehiculos::where('ve_codigo', $req->ve_codigo)->update(['ve_km' => $obj->oa_km]);
            /* return request */
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->oa_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->oa_codigo)?400:404));
        }
    }
}
