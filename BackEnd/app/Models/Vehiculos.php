<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculos extends Model
{
    use HasFactory;

    protected $tabe = 'vehiculos';

    protected $primaryKey = 've_codigo';

    protected $fillable = [
        've_placa',
        've_chasis',
        've_motor',
        'vt_codigo', // tipo
        'vm_codigo', // modelo
        'pa_codigo', // pais
        've_modelo',
        've_anio',
        've_cilindaraje',
        've_capacidadCarga',
        've_capacidadPasajero',
        've_km',
        've_color',
        've_color2',
        've_combustible',
        've_torque',
        've_transmision',
        've_caballos'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        've_estado' => 'boolean'
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
