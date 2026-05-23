<?php

use App\Presentation\Repositories\HistoriasRepository;
use App\Presentation\Repositories\SprintsRepository;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    /*
    |--------------------------------------------------------------------------
    | Ruta principal
    |--------------------------------------------------------------------------
    */

    $app->get('/', function ($request, $response) {

        $response->getBody()->write(json_encode([
            'mensaje' => 'API Gestor de Historias de Usuario funcionando correctamente'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    });

    /*
    |--------------------------------------------------------------------------
    | Rutas de Historias
    |--------------------------------------------------------------------------
    */

    $app->group('/historias', function (RouteCollectorProxy $group) {

        // Obtener todas las historias
        $group->get('', [HistoriasRepository::class, 'list']);

        // Obtener historia por ID
        $group->get('/{id}', [HistoriasRepository::class, 'detail']);

        // Crear historia
        $group->post('', [HistoriasRepository::class, 'create']);

        // Actualizar historia
        $group->put('/{id}', [HistoriasRepository::class, 'update']);

        // Eliminar historia
        $group->delete('/{id}', [HistoriasRepository::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Rutas de Sprints
    |--------------------------------------------------------------------------
    */

    $app->group('/sprints', function (RouteCollectorProxy $group) {

        // Obtener todos los sprints
        $group->get('', [SprintsRepository::class, 'list']);

        // Obtener sprint por ID
        $group->get('/{id}', [SprintsRepository::class, 'detail']);

        // Crear sprint
        $group->post('', [SprintsRepository::class, 'create']);

        // Actualizar sprint
        $group->put('/{id}', [SprintsRepository::class, 'update']);

        // Eliminar sprint
        $group->delete('/{id}', [SprintsRepository::class, 'delete']);

        // Obtener historias de un sprint
        $group->get('/{id}/historias', [SprintsRepository::class, 'historiasSprint']);

        // Reporte del sprint
        $group->get('/{id}/reporte', [SprintsRepository::class, 'reporte']);
    });

};