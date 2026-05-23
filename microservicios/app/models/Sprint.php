<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    // Nombre de la tabla
    protected $table = 'sprints';

    // Campos permitidos
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin'
    ];

    // Activar timestamps automáticos
    public $timestamps = true;

    // Relación: un sprint tiene muchas historias
    public function historias()
    {
        return $this->hasMany(HistoriaUsuario::class, 'sprint_id');
    }
}