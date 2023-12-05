<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entidades;
use DB;

class EntidadController extends Controller
{
    public function index() {
        $obj = Entidades::select([DB::raw("entidades.*, kt.kt_nombre, concat(pe.pe_nombre1, ' ', pe.pe_apellido1) as en_representante, di.di_nombre")])
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'entidades.kt_codigo')
        ->leftJoin('personas as pe', 'pe.pe_codigo', '=', 'entidades.pe_codigo')
        ->leftJoin('distritos as di', 'di.di_codigo', '=', 'entidades.di_codigo')
        ->where('en_estado',true)->get();
        return response()->json($obj, 200);
    }

    public function create (Request $req) {
        if (isset($req->vt_codigo) and isset($req->en_placa) and isset($req->en_chasis)/* and isset($req->en_motor)*/) {
            if (!Entidades::where('vt_codigo', $req->vt_codigo)->where('en_placa', $req->en_placa)->orWhere('vt_codigo', $req->vt_codigo)->where('en_placa', $req->en_chasis)->exists()) {
                $obj = $this->save(new Entidades, $req);
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

    public function save (Entidades $obj , Request $req) {
        if (isset($req->kt_codigo))             { $obj->kt_codigo  = $req->kt_codigo; }
        if (isset($req->pe_codigo))             { $obj->pe_codigo  = $req->pe_codigo; }
        if (isset($req->di_codigo))             { $obj->di_codigo  = $req->di_codigo; }
        if (isset($req->en_nombre))             { $obj->en_nombre  = $req->en_nombre; }
        if (isset($req->en_franquicia))         { $obj->en_franquicia  = $req->en_franquicia; }
        if (isset($req->en_especialidad))       { $obj->en_especialidad  = $req->en_especialidad; }
        if (isset($req->en_24horas))            { $obj->en_24horas  = $req->en_24horas; }
        if (isset($req->en_latitud))            { $obj->en_latitud  = $req->en_latitud; }
        if (isset($req->en_longitud))           { $obj->en_longitud  = $req->en_longitud; }
        if (isset($req->en_plus_code))          { $obj->en_plus_code  = $req->en_plus_code; }
        if (isset($req->en_estado))             { $obj->en_estado  = $req->en_estado; }

        /*existing data*/
        if (isset($obj->en_codigo) and (isset($req->updated_by) or isset($req->us_codigo))) { $obj->updated_by  = (isset($req->updated_by)?$req->updated_by:$req->us_codigo); }
        /*new data*/
        if (is_null($obj->en_codigo)) {
            if (isset($req->created_by) or isset($req->us_codigo)) { $obj->created_by  = (isset($req->created_by)?$req->created_by:$req->us_codigo); }
            $obj->updated_at = null;
        }
        $obj->save();
        return $obj;
    }

    public function read ($id) {
        $obj = ($id === 'all')
        ? Entidades::select([DB::raw("entidades.*, kt.kt_nombre, concat(pe.pe_nombre1, ' ', pe.pe_apellido1) as en_representante, di.di_nombre")])
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'entidades.kt_codigo')
        ->leftJoin('personas as pe', 'pe.pe_codigo', '=', 'entidades.pe_codigo')
        ->leftJoin('distritos as di', 'di.di_codigo', '=', 'entidades.di_codigo')->get()
        : Entidades::select([DB::raw("entidades.*, kt.kt_nombre, concat(pe.pe_nombre1, ' ', pe.pe_apellido1) as en_representante, di.di_nombre")])
        ->leftJoin('contrato_tipos as kt', 'kt.kt_codigo', '=', 'entidades.kt_codigo')
        ->leftJoin('personas as pe', 'pe.pe_codigo', '=', 'entidades.pe_codigo')
        ->leftJoin('distritos as di', 'di.di_codigo', '=', 'entidades.di_codigo')->find($id);
        return response()->json($obj, (strlen(serialize($obj))>2?200:404));
    }

    public function update (Request $req) {
        if (Entidades::where('en_codigo', $req->en_codigo)->exists()) {
            $obj = Entidades::find($req->en_codigo);
            $obj = $this->save($obj, $req);
            return response()->json($obj, 202);
        }
        else {
            return response()->make((!isset($req->en_codigo)?'Petición incorrecta':'No encontrado'), (!isset($req->en_codigo)?400:404));
        }
    }


    public function getEntitiesFromContract ($id = -1, $type = '') {
        $obj = Entidades::select([DB::raw("ke.ke_codigo, ke.ko_codigo, entidades.*, ke.ke_estado, concat(pe.pe_nombre1, ' ', pe.pe_apellido1, ' ' ) as pe_nombres, di.di_nombre")])
            ->leftJoin('distritos as di', 'di.di_codigo', '=', 'entidades.di_codigo')
            ->leftJoin('contrato_entidades as ke', function ($join) use ($id){
                $join->on('ke.en_codigo', '=', 'entidades.en_codigo')
                     ->on('ke.ko_codigo', '=', DB::raw($id))
                     ->on('ke.ke_estado', 'entidades.en_estado');
            })
            ->leftJoin('personas as pe', function ($join) {
                $join->on('pe.pe_codigo', '=', 'entidades.pe_codigo')
                     ->on('pe.pe_estado', 'entidades.en_estado');
            })
            ->where('entidades.en_estado',true)
            ->where('entidades.kt_codigo','like',(strlen($type)?$type:'%%'))
            ->orderBy(DB::raw('IFNULL(cast(ke.ke_codigo as SIGNED)*-1,entidades.en_codigo)')/*'IFNULL(cast(eu.eu_codigo as SIGNED)*-1,usuarios.us_codigo)', 'ASC'*/)
            ->get();
        return response()->json($obj, 200);
    }

    public function saveEntitiesFromContract (Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['en_codigo']) and isset($objs[0]['ko_codigo'])) {
            foreach ($objs as $obj) {
                if (isset($obj['ke_codigo']) and !is_null($obj['ke_codigo'])) {
                    //update existing
                    DB::table('contrato_entidades')->where('ke_codigo',$obj['ke_codigo'])->update(['en_codigo' => $obj['en_codigo'], 'ko_codigo' => $obj['ko_codigo'], 'ke_estado' => (isset($obj['ke_estado'])?$obj['ke_estado']:true), 'updated_by' => (isset($obj['us_edit'])?$obj['us_edit']:null)]);
                }
                else if (isset($obj['ke_estado']) and (bool)$obj['ke_estado']){ /*only save actives*/
                    //Create new
                    DB::insert('insert into contrato_entidades (en_codigo, ko_codigo, ke_estado, created_by) values (?, ?, ?, ?)', [$obj['en_codigo'], $obj['ko_codigo'], true, (isset($obj['us_edit'])?$obj['us_edit']:null)]);
                }
            }
            return $this->getEntitiesFromContract($objs[0]['ko_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }
}
