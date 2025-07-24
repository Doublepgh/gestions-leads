<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'empresa',
        'interes',
        'estatus',
        'creado_por',
    ];
    public $timestamps = false;

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }
}
