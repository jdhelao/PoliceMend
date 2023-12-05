<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contactos;
use App\Models\TipoContactos;
use DB;

class ContactoController extends Controller
{
    public function tipos($id = '') {
        $obj = TipoContactos::where('tc_estado',true)->where('tc_codigo','like',(strlen($id)?$id:'%%'))->get();
        return response()->json($obj, 200);
    }

    public function personas($id = '') {
        $obj = Contactos::where('co_estado',true)->where('pe_codigo','like',(strlen($id)?$id:'%%'))->get();
        //$obj = Contactos::select([DB::raw("contactos.*, tipo_contactos.*")])->join('tipo_contactos', 'tipo_contactos.tc_codigo', '=', 'contactos.tc_codigo')->where('co_estado',true)->where('pe_codigo','like',(strlen($id)?$id:'%%'))->get();
        return response()->json($obj, 200);
    }

    public function save (Request $req) {
        $objs = $req->input();
        if (is_array($objs) and sizeof($objs) > 0 and isset($objs[0]['pe_codigo']) and isset($objs[0]['tc_codigo']) and isset($objs[0]['co_descripcion'])) {
            foreach ($objs as $obj) {

                $co = null;
                if (isset($obj['co_codigo']) and !is_null($obj['co_codigo'])) {
                    /*update existing contac*/
                    $co = Contactos::find($obj['co_codigo']);
                    if (isset($obj['us_codigo'])) { $co->updated_by = $obj['us_codigo']; }
                }
                else {
                    /*Create new contact*/
                    $co = new Contactos;
                    $co->updated_at = null;
                    if (isset($obj['us_codigo'])) { $co->created_by = $obj['us_codigo']; }
                }
                if (isset($obj['pe_codigo']))       { $co->pe_codigo        = $obj['pe_codigo']; }
                if (isset($obj['tc_codigo']))       { $co->tc_codigo        = $obj['tc_codigo']; }
                if (isset($obj['co_descripcion']))  { $co->co_descripcion   = $obj['co_descripcion']; }
                if (isset($obj['co_estado']))       { $co->co_estado        = $obj['co_estado']; }
                $co->save();
            }
            return $this->personas($objs[0]['pe_codigo']);
        }
        else {
            return response()->make(['message' => 'Petición incorrecta'], 400);
        }
    }
}
