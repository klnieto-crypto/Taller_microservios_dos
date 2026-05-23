<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaUsuario extends Model
{
    // Nombre de la tabla
    protected $table = 'historias';

    // Campos que se pueden insertar masivamente
    protected $fillable = [
        'titulo',
        'descripcion',
        'puntos',
        'estado',
        'responsable',
        'sprint_id',
        'fecha_limite'
    ];

    // Activar timestamps automáticos
    public $timestamps = true;

    // Relación: una historia pertenece a un sprint
    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }
}