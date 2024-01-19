<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMovilizaciones extends Model
{
    use HasFactory;

    protected $tabe = 'orden_movilizaciones';

    protected $primaryKey = 'oa_codigo';

    protected $fillable = [
        'sv_codigo', /*ID de la Solicitud de la persona*/
        'en_codigo', /*Entidad donde se realizó el consumo*/
        'oa_total',
        'oa_galones',
        'oa_km',
        'oa_combustible_nivel',
        'oa_documento', /*Numero de factura*/
        'oa_archivo_datos', /*guardar el archivo la factura/voucher in base64*/
        'oa_archivo_tipo', /*guardar la extension del archivo*/
        'oa_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'oa_estado' => 'boolean',
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
