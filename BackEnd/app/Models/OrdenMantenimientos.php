<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMantenimientos extends Model
{
    use HasFactory;

    protected $tabe = 'orden_mantenimientos';

    protected $primaryKey = 'om_codigo';

    protected $fillable = [
        'sv_codigo', /*ID de la Solicitud de la persona*/
        'en_codigo', /*Entidad aprobada*/
        'om_total',
        'pe_codigo', /*tecnico que atiende mantenimiento/reparación*/

        'om_ingreso_aceptacion',
        'om_ingreso_condicion',
        'om_entrega_aceptacion',
        'om_entrega_condicion',
        'om_progreso',

        'om_documento', /*Numero de factura*/
        'om_archivo_datos', /*guardar el archivo la factura/voucher in base64*/
        'om_archivo_tipo', /*guardar la extension del archivo*/
        'om_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'om_ingreso_aceptacion' => 'boolean',
        'om_entrega_aceptacion' => 'boolean',
        'om_estado' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
