<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVehiculos extends Model
{
    use HasFactory;

    protected $tabe = 'solicitud-vehiculos';

    protected $primaryKey = 'sv_codigo';

    protected $fillable = [
        'kt_codigo',
        'pe_codigo',
        've_codigo',
        've_km',
        've_combustible_nivel',
        'sv_fecha_requerimiento',
        'sv_descripcion',
        'sv_aprobacion',
        'sv_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sv_estado' => 'boolean',
        'sv_fecha_requerimiento' => 'datetime',
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
