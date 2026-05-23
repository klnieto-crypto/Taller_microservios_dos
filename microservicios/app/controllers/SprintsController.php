<?php

namespace App\Controllers;

use App\Models\Sprint;
use App\Models\HistoriaUsuario;
use Exception;

class SprintsController
{
    // Obtener todos los sprints
    public function getSprints()
    {
        return Sprint::all();
    }

    // Obtener un sprint por ID
    public function getSprint($id)
    {
        $sprint = Sprint::find($id);

        if (empty($sprint)) {
            throw new Exception("El sprint con ID $id no existe", 404);
        }

        return $sprint;
    }

    // Crear sprint
    public function guardarSprint($data)
    {
        if (
            empty($data['nombre']) ||
            empty($data['fecha_inicio']) ||
            empty($data['fecha_fin'])
        ) {
            throw new Exception("Faltan datos obligatorios del sprint", 400);
        }

        $sprint = new Sprint();

        $sprint->nombre = $data['nombre'];
        $sprint->fecha_inicio = $data['fecha_inicio'];
        $sprint->fecha_fin = $data['fecha_fin'];

        $sprint->save();

        return $sprint;
    }

    // Modificar sprint
    public function modificarSprint($id, $data)
    {
        $sprint = $this->getSprint($id);

        $sprint->nombre = $data['nombre'];
        $sprint->fecha_inicio = $data['fecha_inicio'];
        $sprint->fecha_fin = $data['fecha_fin'];

        $sprint->save();

        return $sprint;
    }

    // Eliminar sprint
    public function borrarSprint($id)
    {
        $sprint = $this->getSprint($id);

        $sprint->delete();

        return true;
    }

    // Obtener historias de un sprint
    public function getHistoriasSprint($sprintId)
    {
        $this->getSprint($sprintId);

        return HistoriaUsuario::where('sprint_id', $sprintId)->get();
    }

    // Reporte general del sprint
    public function reporteSprint($sprintId)
    {
        $this->getSprint($sprintId);

        $historias = HistoriaUsuario::where('sprint_id', $sprintId)->get();

        $total = $historias->count();

        $finalizadas = $historias
            ->where('estado', 'finalizada')
            ->count();

        $activas = $historias
            ->where('estado', 'activa')
            ->count();

        $nuevas = $historias
            ->where('estado', 'nueva')
            ->count();

        $impedimentos = $historias
            ->where('estado', 'impedimento')
            ->count();

        return [
            'sprint_id' => $sprintId,
            'total_historias' => $total,
            'finalizadas' => $finalizadas,
            'activas' => $activas,
            'nuevas' => $nuevas,
            'impedimentos' => $impedimentos
        ];
    }
}