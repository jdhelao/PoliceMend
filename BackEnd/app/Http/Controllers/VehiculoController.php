<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehiculos;
use DB;

class VehiculoController extends Controller
{
    public function index() {
        $obj = Vehiculos::select([DB::raw("vehiculos.*, paises.pa_nombre, vehiculo_marcas.vm_nombre, vehiculo_tipos.vt_nombre")])
        ->leftJoin('paises', 'paises.pa_codigo', '=', 'vehiculos.pa_codigo')
        ->leftJoin('vehiculo_marcas', 'vehiculo_marcas.vm_codigo', '=', 'vehiculos.vm_codigo')
        ->leftJoin('vehiculo_tipos', 'vehiculo_tipos.vt_codigo', '=', 'vehiculos.vt_codigo')
        ->where('ve_estado',true)->get();
        return response()->json($obj, 200);
    }
    public function tipos($id='') {
        $obj = DB::table('vehiculo_tipos')
        ->where('vt_codigo','like',(strlen($id)?$id:'%%'))
        ->where('vt_estado',true)->get();
        return response()->json($obj, 200);
    }
    public function marcas($id='') {
        $obj = DB::table('vehiculo_marcas')
        ->where('vm_codigo','like',(strlen($id)?$id:'%%'))
        ->where('vm_estado',true)->get();
        return response()->json($obj, 200);
    }
    public function modelos($id='') {
        $obj = DB::table('vehiculo_modelos')
        ->where('mm_codigo','like',(strlen($id)?$id:'%%'))
        ->where('mm_estado',true)->get();
        return response()->json($obj, 200);
    }
    public function getVehiclesFromPerson ($id) {
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

    public function getVehicleHistory ($type, $id) {
        $obj = null;
        if ($type == 'gas') { /* Gas levels*/
            $obj = DB::select('SELECT DISTINCT created_at as _date, ve_combustible_nivel as _value
            from solicitud_vehiculos
            where (sv_estado=1) and ve_codigo = '.$id.'
            UNION
            SELECT DISTINCT IFNULL(oa.updated_at,oa.created_at) as _date, oa_combustible_nivel as _value
            from orden_abastecimientos as oa
            inner join solicitud_vehiculos as sv on (sv.sv_codigo=oa.sv_codigo and sv.ve_codigo = '.$id.')
            where (oa_estado=1)
            order by 1');
        }
        else if ($type == 'gal') { /* Gallons */
            $obj = DB::select('SELECT DISTINCT IFNULL(oa.updated_at,oa.created_at) as _date, oa_galones as _value
            from orden_abastecimientos as oa
            inner join solicitud_vehiculos as sv on (sv.sv_codigo=oa.sv_codigo and sv.ve_codigo = '.$id.')
            where (oa_estado=1)
            order by 1');
        }
        else if ($type == 'gal-kms') { /* Consumption of gallons per km */
            $obj = DB::select('SELECT sv.created_at as _date
            #, GREATEST(CAST((((sv.ve_km - oa.oa_km) * (oa.oa_combustible_nivel - sv.ve_combustible_nivel) / 100) / oa.oa_galones) AS DECIMAL(18,2)), 0) as _value
            , GREATEST(cast((((oa.oa_combustible_nivel - sv.ve_combustible_nivel) / 100) * oa.oa_galones) / (sv.ve_km - oa.oa_km) as  DECIMAL(18,2)), 0) AS _value
            #, (($kilometraje_final - $kilometraje_inicial) * ($nivel_tanque_inicial - $nivel_tanque_final       ) / 100) / $galones as _value
            from solicitud_vehiculos as sv
            LEFT OUTER JOIN orden_abastecimientos as oa on (oa.oa_codigo = (
                SELECT ho.oa_codigo
                from orden_abastecimientos as ho
                inner join solicitud_vehiculos as hs on (hs.sv_codigo=ho.sv_codigo)
                WHERE ho.oa_estado = 1 and ho.sv_codigo < sv.sv_codigo and hs.ve_codigo = sv.ve_codigo
                ORDER BY ho.oa_codigo desc
                limit 0,1
            ))
            where (sv.sv_estado = 1) and (sv.ve_codigo = '.$id.')
            #and sv.sv_codigo = 13
            order by 1');
        }
        else if ($type == 'gal-day') { /* Consumption of gallons per day */
            $obj = DB::select('SELECT sv.created_at as _date
            , GREATEST(cast((((oa.oa_combustible_nivel - sv.ve_combustible_nivel) / 100) * oa.oa_galones) / DATEDIFF(sv.created_at, IFNULL(oa.updated_at,oa.created_at)) as  DECIMAL(18,2)), 0) AS _value
            #, (($kilometraje_final - $kilometraje_inicial) * ($nivel_tanque_inicial - $nivel_tanque_final       ) / 100) / $galones as _value
            from solicitud_vehiculos as sv
            LEFT OUTER JOIN orden_abastecimientos as oa on (oa.oa_codigo = (
                SELECT ho.oa_codigo
                from orden_abastecimientos as ho
                inner join solicitud_vehiculos as hs on (hs.sv_codigo=ho.sv_codigo)
                WHERE ho.oa_estado = 1 and ho.sv_codigo < sv.sv_codigo and hs.ve_codigo = sv.ve_codigo
                ORDER BY ho.oa_codigo desc
                limit 0,1
            ))
            where (sv.sv_estado = 1) and (sv.ve_codigo = '.$id.')
            #and sv.sv_codigo = 13
            order by 1');
        }
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->vt_codigo) and isset($req->ve_placa) and isset($req->ve_chasis)/* and isset($req->ve_motor)*/) {
            if (!Vehiculos::where('vt_codigo', $req->vt_codigo)->where('ve_placa', $req->ve_placa)->orWhere('vt_codigo', $req->vt_codigo)->where('ve_placa', $req->ve_chasis)->exists()) {
                $obj = $this->save(new Vehiculos, $req);
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

    public function save (Vehiculos $obj , Request $req) {
        if (isset($req->ve_placa))              { $obj->ve_placa  = $req->ve_placa; }
        if (isset($req->ve_chasis))             { $obj->ve_chasis  = $req->ve_chasis; }
        if (isset($req->ve_motor))              { $obj->ve_motor  = $req->ve_motor; }
        if (isset($req->vt_codigo))             { $obj->vt_codigo  = $req->vt_codigo; }
        if (isset($req->vm_codigo))             { $obj->vm_codigo  = $req->vm_codigo; }
        if (isset($req->pa_codigo))             { $obj->pa_codigo  = $req->pa_codigo; }
        if (isset($req->ve_modelo))             { $obj->ve_modelo  = $req->ve_modelo; }
        if (isset($req->ve_anio))               { $obj->ve_anio  = $req->ve_anio; }
        if (isset($req->ve_cilindaraje))        { $obj->ve_cilindaraje  = $req->ve_cilindaraje; }
        if (isset($req->ve_capacidadPasajero))  { $obj->ve_capacidadPasajero  = $req->ve_capacidadPasajero; }
        if (isset($req->ve_km))                 { $obj->ve_km  = $req->ve_km; }
        if (isset($req->ve_color))              { $obj->ve_color  = $req->ve_color; }
        if (isset($req->ve_color2))             { $obj->ve_color2  = $req->ve_color2; }
        if (isset($req->ve_combustible))        { $obj->ve_combustible  = $req->ve_combustible; }
        if (isset($req->ve_torque))             { $obj->ve_torque  = $req->ve_torque; }
        if (isset($req->ve_transmision))        { $obj->ve_transmision  = $req->ve_transmision; }
        if (isset($req->ve_caballos))           { $obj->ve_caballos  = $req->ve_caballos; }
        if (isset($req->ve_estado))             { $obj->ve_estado  = $req->ve_estado; }

        /*existing data*/
        if (isset($obj->ve_codigo) and (isset($req->updated_by) or isset($req->us_codigo))) { $obj->updated_by  = (isset($req->updated_by)?$req->updated_by:$req->us_codigo); }
        /*new data*/
        if (is_null($obj->ve_codigo)) {
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
        if (Vehiculos::where('ve_codigo', $req->ve_codigo)->exists()) {
            $obj = Vehiculos::find($req->ve_codigo);
            $obj = $this->save($obj, $req);
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->ve_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->ve_codigo)?400:404));
        }
    }
}
