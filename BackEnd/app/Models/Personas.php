<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personas extends Model
{
    use HasFactory;

    protected $tabe = 'personas';

    protected $primaryKey = 'pe_codigo';

    protected $fillable = [
        'pe_dni',
        'pe_nombre1',
        'pe_nombre2',
        'pe_apellido1',
        'pe_apellido2',
        'pe_sangre',
        'pe_fnacimiento',
        'ci_codigo',
        'ra_codigo'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pe_estado' => 'boolean',
        'pe_fnacimiento' => 'datetime',
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
