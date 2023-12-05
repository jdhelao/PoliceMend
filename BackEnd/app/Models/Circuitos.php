<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuitos extends Model
{
    use HasFactory;

    protected $tabe = 'circuitos';

    protected $primaryKey = 'cc_codigo';
    public $incrementing = false;

    protected $fillable = [
        'di_codigo',
        'cc_nombre',
        'cc_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cc_estado' => 'boolean',
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
