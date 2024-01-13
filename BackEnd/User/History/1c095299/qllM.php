<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVehiculos;
use App\Models\VehiculoHistoriales;
use App\Models\Vehiculos;
use App\Models\OrdenMantenimientoActividades;
use DB;
use Carbon\Carbon;

class OrdenMantenimientoActividadController extends Controller
{
    public function getActivitiesFromOrder ($id) {
        $obj = OrdenMantenimientoActividades::where('om_codigo',$id)
        ->where('oma_estado',true)
        ->get();
        return response()->json($obj, 200);
    }

    public function save(Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['om_codigo']) and isset($objs[0]['oma_descripcion'])) {
            foreach ($objs as $obj) {
                if (isset($obj['oma_codigo']) and !is_null($obj['om_codigo'])) {
                    //update existing
                    //DB::table('contrato_entidades')->where('ke_codigo',$obj['ke_codigo'])->update(['en_codigo' => $obj['en_codigo'], 'ko_codigo' => $obj['ko_codigo'], 'ke_estado' => (isset($obj['ke_estado'])?$obj['ke_estado']:true), 'updated_by' => (isset($obj['us_edit'])?$obj['us_edit']:null)]);
                    //OrdenMantenimientoActividades::where('oma_codigo',$obj['oma_codigo'])->update(['oma_descripcion' => $obj->oma_descripcion, 'oma_costo' => $obj->oma_costo, 'oma_estado' => $obj->oma_estado, 'updated_by' => ($req->updated_by??$req->us_codigo??null),]);
                    DB::table('orden_mantenimiento_actividades')->where('oma_codigo',$obj['oma_codigo'])->update(['oma_estado' => $obj->oma_estado]);
                }
                else if (isset($obj['ke_estado']) and (bool)$obj['ke_estado']){ /*only save actives*/
                    //Create new
                    //DB::insert('insert into contrato_entidades (en_codigo, ko_codigo, ke_estado, created_by) values (?, ?, ?, ?)', [$obj['en_codigo'], $obj['ko_codigo'], true, (isset($obj['us_edit'])?$obj['us_edit']:null)]);
                    OrdenMantenimientoActividades::create(['om_codigo' => $obj->om_codigo, 'oma_descripcion' => $obj->oma_descripcion, 'oma_costo' => $obj->oma_costo, 'created_by' => ($req->created_by??$req->us_codigo??null),]);
                }
            }
            return $this->getActivitiesFromOrder($objs[0]['om_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }
}
