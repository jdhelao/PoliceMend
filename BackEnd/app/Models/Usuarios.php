<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    use HasFactory;

    protected $tabe = 'usuarios';

    protected $primaryKey = 'us_codigo';

    protected $fillable = [
        'us_login',
        'pe_codigo',
        'pf_codigo',
        'us_password',
        'us_estado',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'us_estado' => 'boolean',
        /*'updated_at' => 'datetime',*/
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'us_password',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}
