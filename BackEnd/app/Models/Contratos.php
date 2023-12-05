<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratos extends Model
{
    use HasFactory;

    protected $tabe = 'contratos';

    protected $primaryKey = 'ko_codigo';

    protected $fillable = [
        'ko_documento',
        'kt_codigo',
        'ko_inicio',
        'ko_fin',
        'ko_monto',
        'ko_compadecientes',
        'ko_clausulas',
        'kt_contratante',
        'kt_contratista',
        'ko_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ko_estado' => 'boolean',
        'ko_inicio' => 'datetime',
        'ko_fin' => 'datetime'
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
