<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogos extends Model
{
    use HasFactory;

    protected $tabe = 'catalogos';

    protected $primaryKey = 'ca_codigo';
    public $incrementing = false;

    protected $fillable = [
        'ca_nombre',
        'ca_prefijo',
        'ca_tabla',
        'ca_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ca_estado' => 'boolean',
        /*'updated_at' => 'datetime',*/
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
