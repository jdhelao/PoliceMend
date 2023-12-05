<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcircuitos extends Model
{
    use HasFactory;

    protected $tabe = 'subcircuitos';

    protected $primaryKey = 'sc_codigo';
    public $incrementing = false;

    protected $fillable = [
        'cc_codigo',
        'sc_nombre',
        'sc_estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sc_estado' => 'boolean',
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
