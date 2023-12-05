<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContactos extends Model
{
    use HasFactory;

    protected $tabe = 'tipo_contactos';

    protected $primaryKey = 'tc_codigo';

    protected $fillable = [
        'tc_nombre',
        'tc_min',
        'tc_max',
        'tc_rex',
        'tc_ico'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tc_estado' => 'boolean'
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
