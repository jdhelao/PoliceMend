<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMovilizaciones extends Model
{
    use HasFactory;

    protected $tabe = 'orden_movilizaciones';

    protected $primaryKey = 'od_codigo';

    protected $fillable = [
        'sv_codigo', // ID de la Solicitud de la persona
        'od_ocupantes', // Lista de ocupantes
        'od_estado',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'od_estado' => 'boolean',
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
