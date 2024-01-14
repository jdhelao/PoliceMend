<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMantenimientoRepuestos extends Model
{
    use HasFactory;

    protected $tabe = 'orden_mantenimiento_repuestos';

    protected $primaryKey = 'omr_codigo';

    protected $fillable = [
        'om_codigo', // ID de la Orde de Mantenimiento
        're_codigo', // ID del Repuesto
        'omr_cantidad',
        'omr_costo',

        'omr_estado',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'omr_estado' => 'boolean',
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
