<?php

namespace App\Presentation\Repositories;

use App\Controllers\SprintsController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SprintsRepository
{
    // Listar sprints
    public function list(Request $request, Response $response)
    {
        $controller = new SprintsController();

        $sprints = $controller->getSprints();

        $response->getBody()->write($sprints->toJson());

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    // Obtener sprint
    public function detail(Request $request, Response $response, $args)
    {
        try {

            $id = $args['id'];

            $controller = new SprintsController();

            $sprint = $controller->getSprint($id);

            $response->getBody()->write($sprint->toJson());

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');

        } catch (Exception $ex) {

            $response->getBody()->write(json_encode([
                'msg' => $ex->getMessage()
            ]));

            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    // Crear sprint
    public function create(Request $request, Response $response)
    {
        try {

            $body = $request->getBody()->getContents();

            $data = json_decode($body, true);

            $controller = new SprintsController();

            $sprint = $controller->guardarSprint($data);

            $response->getBody()->write($sprint->toJson());

            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');

        } catch (Exception $ex) {

            $response->getBody()->write(json_encode([
                'msg' => $ex->getMessage()
            ]));

            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    // Actualizar sprint
    public function update(Request $request, Response $response, $args)
    {
        try {

            $id = $args['id'];

            $body = $request->getBody()->getContents();

            $data = json_decode($body, true);

            $controller = new SprintsController();

            $sprint = $controller->modificarSprint($id, $data);

            $response->getBody()->write($sprint->toJson());

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');

        } catch (Exception $ex) {

            $response->getBody()->write(json_encode([
                'msg' => $ex->getMessage()
            ]));

            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    // Eliminar sprint
    public function delete(Request $request, Response $response, $args)
    {
        try {

            $id = $args['id'];

            $controller = new SprintsController();

            $controller->borrarSprint($id);

            $response->getBody()->write(json_encode([
                'msg' => 'Sprint eliminado correctamente'
            ]));

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');

        } catch (Exception $ex) {

            $response->getBody()->write(json_encode([
                'msg' => $ex->getMessage()
            ]));

            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    // Historias por sprint
    public function historiasSprint(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        $controller = new SprintsController();

        $historias = $controller->getHistoriasSprint($id);

        $response->getBody()->write($historias->toJson());

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    // Reporte del sprint
    public function reporte(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        $controller = new SprintsController();

        $reporte = $controller->reporteSprint($id);

        $response->getBody()->write(json_encode($reporte));

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}