<?php

namespace App\Models;

use App\Models\Asignacion;
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
        'operador_id',
    ];
    public $timestamps = false;

    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    public function operador()
{
    return $this->belongsTo(User::class, 'operador_id');
}
}
