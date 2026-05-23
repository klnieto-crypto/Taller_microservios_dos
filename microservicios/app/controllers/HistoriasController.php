<?php

namespace App\Controllers;

use App\Models\HistoriaUsuario;
use Exception;

class HistoriasController
{
    // Obtener todas las historias
    public function getHistorias()
    {
        return HistoriaUsuario::all();
    }

    // Obtener una historia por ID
    public function getHistoria($id)
    {
        $historia = HistoriaUsuario::find($id);

        if (empty($historia)) {
            throw new Exception("La historia con ID $id no existe", 404);
        }

        return $historia;
    }

    // Crear nueva historia
    public function guardarHistoria($data)
    {
        // Validaciones obligatorias
        if (
            empty($data['titulo']) ||
            empty($data['descripcion']) ||
            empty($data['estado']) ||
            empty($data['sprint_id'])
        ) {
            throw new Exception("Faltan datos obligatorios", 400);
        }

        // Estados válidos
        $estadosValidos = [
            'nueva',
            'activa',
            'finalizada',
            'impedimento'
        ];

        if (!in_array($data['estado'], $estadosValidos)) {
            throw new Exception("Estado no válido", 400);
        }

        $historia = new HistoriaUsuario();

        $historia->titulo = $data['titulo'];
        $historia->descripcion = $data['descripcion'];

        $historia->puntos = empty($data['puntos'])
            ? 0
            : $data['puntos'];

        $historia->estado = $data['estado'];

        $historia->responsable = empty($data['responsable'])
            ? null
            : $data['responsable'];

        $historia->sprint_id = $data['sprint_id'];

                $historia->fecha_creacion = $data['fecha_creacion'] 
                ?? date('Y-m-d');

           $historia->fecha_finalizacion = empty($data['fecha_finalizacion'])
            ? null
            : $data['fecha_finalizacion'];

        $historia->save();

        return $historia;
    }

    // Modificar historia
    public function modificarHistoria($id, $data)
    {
        $historia = $this->getHistoria($id);

        $estadosValidos = [
            'nueva',
            'activa',
            'finalizada',
            'impedimento'
        ];

        if (!in_array($data['estado'], $estadosValidos)) {
            throw new Exception("Estado no válido", 400);
        }

        $historia->titulo = $data['titulo'];
        $historia->descripcion = $data['descripcion'];

        $historia->puntos = empty($data['puntos'])
            ? 0
            : $data['puntos'];

        $historia->estado = $data['estado'];

        $historia->responsable = empty($data['responsable'])
            ? null
            : $data['responsable'];

        $historia->sprint_id = $data['sprint_id'];

        $historia->fecha_creacion = $data['fecha_creacion'] 
        ?? date('Y-m-d');

      $historia->fecha_finalizacion = empty($data['fecha_finalizacion'])
            ? null
            : $data['fecha_finalizacion'];

        $historia->save();

        return $historia;
    }
    // Eliminar historia
    public function borrarHistoria($id){
       $historia = $this->getHistoria($id);

        $historia->delete();

        return true;
    }
}