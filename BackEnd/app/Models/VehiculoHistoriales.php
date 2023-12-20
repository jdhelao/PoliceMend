<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoHistoriales extends Model
{
    use HasFactory;

    protected $tabe = 'vehiculo_historiales';

    protected $primaryKey = 'vh_codigo';

    protected $fillable = [
        've_codigo',
        'vh_tipo',
        'vh_valor',

        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vh_estado' => 'boolean'
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
