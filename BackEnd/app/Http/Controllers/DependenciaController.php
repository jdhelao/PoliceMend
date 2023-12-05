<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcircuitos;
use App\Models\Usuarios;
use DB;

class DependenciaController extends Controller
{
    public function getFromPerson ($id = -1) {
        $obj = Subcircuitos::where('sc_estado',true)
        ->join('persona_subcircuitos', 'persona_subcircuitos.sc_codigo', '=', 'subcircuitos.sc_codigo')->where('ps_estado',true)
        ->select([DB::raw("persona_subcircuitos.*, subcircuitos.cc_codigo ,left(subcircuitos.sc_codigo,5) as di_codigo")])->where('pe_codigo',$id)
        ->get();
        return response()->json($obj, 200);
    }

    public function saveFromPerson (Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['pe_codigo']) and isset($objs[0]['sc_codigo'])) {
            foreach ($objs as $obj) {
                if (isset($obj['ps_codigo']) and !is_null($obj['ps_codigo'])) {
                    //update existing
                    DB::table('persona_subcircuitos')->where('ps_codigo',$obj['ps_codigo'])->update(['pe_codigo' => $obj['pe_codigo'], 'sc_codigo' => $obj['sc_codigo'], 'ps_estado' => (isset($obj['ps_estado'])?$obj['ps_estado']:true), 'updated_by' => (isset($obj['us_codigo'])?$obj['us_codigo']:null)]);
                }
                else {
                    //Create new
                    DB::insert('insert into persona_subcircuitos (pe_codigo, sc_codigo, ps_estado, created_by) values (?, ?, ?, ?)', [$obj['pe_codigo'], $obj['sc_codigo'], (isset($obj['ps_estado'])?$obj['ps_estado']:true), (isset($obj['us_codigo'])?$obj['us_codigo']:null)]);
                }
            }
            return $this->getFromPerson($objs[0]['pe_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrectaaa'], 400);
        }
    }

    public function getFromVehicle ($id = -1) {
        $obj = Subcircuitos::where('sc_estado',true)
        ->join('vehiculo_subcircuitos', 'vehiculo_subcircuitos.sc_codigo', '=', 'subcircuitos.sc_codigo')->where('vs_estado',true)
        ->select([DB::raw("vehiculo_subcircuitos.*, subcircuitos.cc_codigo ,left(subcircuitos.sc_codigo,5) as di_codigo")])->where('ve_codigo',$id)
        ->get();
        return response()->json($obj, 200);
    }

    public function saveFromVehiculo (Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['ve_codigo']) and isset($objs[0]['sc_codigo'])) {
            foreach ($objs as $obj) {
                if (isset($obj['vs_codigo']) and !is_null($obj['vs_codigo'])) {
                    //update existing
                    DB::table('vehiculo_subcircuitos')->where('vs_codigo',$obj['vs_codigo'])->update(['ve_codigo' => $obj['ve_codigo'], 'sc_codigo' => $obj['sc_codigo'], 'vs_estado' => (isset($obj['vs_estado'])?$obj['vs_estado']:true), 'updated_by' => (isset($obj['us_codigo'])?$obj['us_codigo']:null)]);
                }
                else {
                    //Create new
                    DB::insert('insert into vehiculo_subcircuitos (ve_codigo, sc_codigo, vs_estado, created_by) values (?, ?, ?, ?)', [$obj['ve_codigo'], $obj['sc_codigo'], (isset($obj['vs_estado'])?$obj['vs_estado']:true), (isset($obj['us_codigo'])?$obj['us_codigo']:null)]);
                }
            }
            return $this->getFromVehicle($objs[0]['ve_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrectaaa'], 400);
        }
    }

    /**
     * Get list of elegible Custodians from Depedencies (Subcicuitos) to asign to Vehicles
     */
    public function getCustodiansFromDependencies (Request $req) {
        $objs = $req->input();
        $list = [];
        $ve_code = null;

        /*Set list of Subcircuits*/
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['ve_codigo']) and isset($objs[0]['sc_codigo'])) {
            $ve_code = $objs[0]['ve_codigo'];
            foreach ($objs as $obj) {
                if ( /*new row for subcircuit*/!isset($obj['vs_estado']) or  (/*existing*/isset($obj['vs_estado']) and /*active*/(bool)$obj['vs_estado'])) {
                    $list[] = $obj['sc_codigo'];
                }
            }

            $obj = Subcircuitos::where('subcircuitos.sc_estado',true)->whereIn('subcircuitos.sc_codigo', $list)
            //->join('persona_subcircuitos as ps', 'ps.sc_codigo', '=', 'ps.sc_codigo','ps.ps_estado','=',true)//->On('ps.ps_estado','=',true)
            //->leftJoin('vehiculo_custodios as vc', 'vc.pe_codigo', '=', 'ps.pe_codigo','vc.vc_estado','=',true)//->On('vc.vc_estado','=',true)
            ->join('persona_subcircuitos as ps', function ($join) {
                $join->on('ps.sc_codigo', '=', 'subcircuitos.sc_codigo')
                     ->on('ps.ps_estado', 'sc_estado');
            })
            ->leftJoin('vehiculo_custodios as vc', function ($join) use ($ve_code){
                $join->on('vc.pe_codigo', '=', 'ps.pe_codigo')
                     ->on('vc.ve_codigo', '=', DB::raw($ve_code))
                     ->on('vc.vc_estado', 'sc_estado');
            })
            ->join('personas as pe', function ($join) {
                $join->on('pe.pe_codigo', '=', 'ps.pe_codigo')
                     ->on('pe.pe_estado', 'sc_estado');
            })
            ->join('rangos as ra', function ($join) {
                /*get only people with Rank*/
                $join->on('ra.ra_codigo', '=', 'pe.ra_codigo')
                     ->on('ra.ra_codigo', '>=', 'sc_estado');/*avoid ID: 0 Rank: Ninguno*/
            })
            ->select([DB::raw("vc.vc_codigo, ". (int)$objs[0]['ve_codigo'] ." as ve_codigo, ps.pe_codigo, vc.vc_estado, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres, ra.ra_nombre")])
            ->get();
            return response()->json($obj, 200);

        }
        else {
            return response()->make(['message' => 'Petición incorrectaaa'], 400);
        }
    }

    public function saveCustodiansFromDependencies (Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['ve_codigo']) and isset($objs[0]['pe_codigo'])) {
            foreach ($objs as $obj) {
                if (isset($obj['vc_codigo']) and !is_null($obj['vc_codigo'])) {
                    //update existing
                    DB::table('vehiculo_custodios')->where('vc_codigo',$obj['vc_codigo'])->update(['ve_codigo' => $obj['ve_codigo'], 'pe_codigo' => $obj['pe_codigo'], 'vc_estado' => (isset($obj['vc_estado'])?$obj['vc_estado']:true), 'updated_by' => (isset($obj['us_codigo'])?$obj['us_codigo']:null)]);
                }
                else if (isset($obj['vc_estado']) and (bool)$obj['vc_estado']){ /*only save active person*/
                    //Create new
                    DB::insert('insert into vehiculo_custodios (ve_codigo, pe_codigo, vc_estado, created_by) values (?, ?, ?, ?)', [$obj['ve_codigo'], $obj['pe_codigo'], $obj['vc_estado'], (isset($obj['us_codigo'])?$obj['us_codigo']:null)]);
                }
            }
            return $this->getCustodiansFromVehicle($objs[0]['ve_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }

    public function getCustodiansFromVehicle ($id = -1) {
        $obj = Subcircuitos::where('subcircuitos.sc_estado',true)
            ->join('vehiculo_subcircuitos as vs', function ($join) use ($id){
                $join->on('vs.sc_codigo', '=', 'subcircuitos.sc_codigo')
                    ->on('vs.ve_codigo', '=', DB::raw($id));
            })
            ->join('persona_subcircuitos as ps', function ($join) {
                $join->on('ps.sc_codigo', '=', 'subcircuitos.sc_codigo')
                     ->on('ps.ps_estado', 'sc_estado');
            })
            ->leftJoin('vehiculo_custodios as vc', function ($join) {
                $join->on('vc.pe_codigo', '=', 'ps.pe_codigo')
                     ->on('vc.ve_codigo', '=', 'vs.ve_codigo')
                     ->on('vc.vc_estado', 'sc_estado');
            })
            ->join('personas as pe', function ($join) {
                $join->on('pe.pe_codigo', '=', 'ps.pe_codigo')
                     ->on('pe.pe_estado', 'sc_estado');
            })
            ->join('rangos as ra', function ($join) {
                /*get only people with Rank*/
                $join->on('ra.ra_codigo', '=', 'pe.ra_codigo')
                     ->on('ra.ra_codigo', '>=', 'sc_estado');/*avoid ID: 0 Rank: Ninguno*/
            })
            ->select([DB::raw("vc.vc_codigo, vs.ve_codigo, ps.pe_codigo, vc.vc_estado, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres, ra.ra_nombre")])
            ->get();
        return response()->json($obj, 200);
    }

    public function getUsersFromEntity ($id = -1) {
        $obj = Usuarios::where('usuarios.us_estado',true)
            ->leftJoin('perfiles as pf', 'pf.pf_codigo', '=', 'usuarios.pf_codigo')
            ->leftJoin('entidad_usuarios as eu', function ($join) use ($id){
                $join->on('eu.us_codigo', '=', 'usuarios.us_codigo')
                     ->on('eu.en_codigo', '=', DB::raw($id))
                     ->on('eu.eu_estado', 'usuarios.us_estado');
            })
            ->leftJoin('personas as pe', function ($join) {
                $join->on('pe.pe_codigo', '=', 'usuarios.pe_codigo')
                     ->on('pe.pe_estado', 'usuarios.us_estado');
            })
            ->select([DB::raw("eu.eu_codigo, eu.en_codigo, usuarios.us_codigo, eu.eu_estado, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres, usuarios.us_login, pf.pf_nombre")])
            ->orderBy(DB::raw('IFNULL(cast(eu.eu_codigo as SIGNED)*-1,usuarios.us_codigo)')/*'IFNULL(cast(eu.eu_codigo as SIGNED)*-1,usuarios.us_codigo)', 'ASC'*/)
            ->get();
        return response()->json($obj, 200);
    }

    public function saveUsersFromEntity (Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['en_codigo']) and isset($objs[0]['us_codigo'])) {
            foreach ($objs as $obj) {
                if (isset($obj['eu_codigo']) and !is_null($obj['eu_codigo'])) {
                    //update existing
                    DB::table('entidad_usuarios')->where('eu_codigo',$obj['eu_codigo'])->update(['en_codigo' => $obj['en_codigo'], 'us_codigo' => $obj['us_codigo'], 'eu_estado' => (isset($obj['eu_estado'])?$obj['eu_estado']:true), 'updated_by' => (isset($obj['us_edit'])?$obj['us_edit']:null)]);
                }
                else if (isset($obj['eu_estado']) and (bool)$obj['eu_estado']){ /*only save active person*/
                    //Create new
                    DB::insert('insert into entidad_usuarios (en_codigo, us_codigo, eu_estado, created_by) values (?, ?, ?, ?)', [$obj['en_codigo'], $obj['us_codigo'], $obj['eu_estado'], (isset($obj['us_edit'])?$obj['us_edit']:null)]);
                }
            }
            return $this->getUsersFromEntity($objs[0]['en_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }


}
