<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenAbastecimientos extends Model
{
    use HasFactory;

    protected $tabe = 'orden_abastecimientos';

    protected $primaryKey = 'oa_codigo';

    protected $fillable = [
        'sv_codigo',
        'en_codigo',
        'oa_total',
        'oa_galones',
        'oa_combustible_nivel',
        'oa_documento',
        'oa_archivo_datos',
        'oa_archivo_tipo',
        'oa_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'oa_estado' => 'boolean',
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
