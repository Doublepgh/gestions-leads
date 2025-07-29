<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\Models\User;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    protected $fillable = [
        'lead_id',
        'operador_id',
        'asignado_en',
        'cerrado_en',
    ];

    public $timestamps = false;


    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }
}
