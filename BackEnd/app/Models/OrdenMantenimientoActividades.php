<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMantenimientoActividades extends Model
{
    use HasFactory;

    protected $tabe = 'orden_mantenimiento_actividades';

    protected $primaryKey = 'oma_codigo';

    protected $fillable = [
        'om_codigo', // ID de la Orde de Mantenimiento
        'oma_descripcion',
        'oma_costo',

        'oma_estado',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'oma_estado' => 'boolean',
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
