<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use App\Models\VehiculoHistoriales;
use App\Models\Vehiculos;
use App\Models\OrdenMantenimientoRepuestos;
use App\Models\Repuestos;
use DB;
use Carbon\Carbon;

class OrdenMantenimientoRepuestoController extends Controller
{
    public function getSparePartsFromOrder ($id) {
        $obj = Repuestos::select([DB::raw("omr.omr_codigo, ".$id." as om_codigo, repuestos.re_codigo, repuestos.re_nombre, omr.omr_cantidad, omr.omr_costo, omr.omr_estado")])
        ->leftJoin('orden_mantenimiento_repuestos as omr', function ($join) {
            $join->on('omr.re_codigo', '=', 'repuestos.re_codigo')
                 ->on('omr.omr_estado', 'repuestos.re_estado');
        })
        ->where('re_estado',true)
        ->orderBy(DB::raw('IFNULL(cast(omr.omr_codigo as SIGNED)*-1,repuestos.re_nombre)'))
        ->get();
        return response()->json($obj, 200);
    }

    public function save(Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['om_codigo']) and isset($objs[0]['omr_cantidad'])) {
            foreach ($objs as $obj) {
                if (isset($obj['omr_codigo']) and !is_null($obj['om_codigo'])) {
                    // update existing
                    //DB::table('contrato_entidades')->where('ke_codigo',$obj['ke_codigo'])->update(['en_codigo' => $obj['en_codigo'], 'ko_codigo' => $obj['ko_codigo'], 'ke_estado' => (isset($obj['ke_estado'])?$obj['ke_estado']:true), 'updated_by' => ($obj['us_codigo']??$obj['us_edit']??null)]);
                    OrdenMantenimientoRepuestos::where('omr_codigo',$obj['omr_codigo'])->update(['re_codigo' => $obj['re_codigo'], 'omr_cantidad' => $obj['omr_cantidad'], 'omr_costo' => $obj['omr_costo'], 'omr_estado' => $obj['omr_estado'], 'updated_by' => ($obj['us_codigo']??$obj['us_edit']??null),]);
                    //DB::table('orden_mantenimiento_actividades')->where('omr_codigo',$obj['omr_codigo'])->update(['omr_estado' => $obj->omr_estado]);
                }
                else if (isset($obj['om_codigo']) and (bool)$obj['omr_estado']){ /*only save actives*/
                    // Create new
                    //DB::insert('insert into orden_mantenimiento_actividades (om_codigo, omr_descripcion, omr_costo, omr_estado, created_by) values (?, ?, ?, ?, ?)', [$obj['om_codigo'], $obj['omr_descripcion'], $obj['omr_costo'], true, ($obj['us_codigo']??$obj['us_edit']??null)]);
                    OrdenMantenimientoRepuestos::create(['om_codigo' => $obj['om_codigo'], 're_codigo' => $obj['re_codigo'], 'omr_cantidad' => $obj['omr_cantidad'], 'omr_costo' => $obj['omr_costo'], 'created_by' => ($obj['us_codigo']??$obj['us_edit']??null),]);
                }
            }
            return $this->getSparePartsFromOrder($objs[0]['om_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }
}
