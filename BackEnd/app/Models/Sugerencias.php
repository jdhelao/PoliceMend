<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugerencias extends Model
{
    use HasFactory;

    protected $tabe = 'sugerencias';

    protected $primaryKey = 'su_codigo';
    public $timestamps = false;

    protected $fillable = [
        'su_tipo',
        'sc_codigo',
        'su_contacto',
        'su_nombres',
        'su_apellidos',
        'su_detalles',
        'su_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'su_estado' => 'boolean',
        /*'updated_at' => 'datetime',*/
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at'/*,
        'created_by',
        'updated_at',
        'updated_by'*/
    ];
}
