<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidades extends Model
{
    use HasFactory;

    protected $tabe = 'entidades';

    protected $primaryKey = 'en_codigo';

    protected $fillable = [
        'kt_codigo',
        'pe_codigo',
        'di_codigo',
        'en_nombre',
        'en_franquicia',
        'en_especialidad',
        'en_24horas',
        'en_latitud',
        'en_longitud',
        'en_plus_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'en_estado' => 'boolean',
        'en_24horas' => 'boolean'
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
