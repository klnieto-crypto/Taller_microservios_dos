<?php

namespace App\Presentation\Repositories;

use App\Controllers\HistoriasController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HistoriasRepository
{
    // Listar historias
    public function list(Request $request, Response $response)
    {
        $controller = new HistoriasController();

        $historias = $controller->getHistorias();

        $response->getBody()->write($historias->toJson());

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    // Obtener una historia
    public function detail(Request $request, Response $response, $args)
    {
        try {

            $id = $args['id'];

            $controller = new HistoriasController();

            $historia = $controller->getHistoria($id);

            $response->getBody()->write($historia->toJson());

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

    // Crear historia
    public function create(Request $request, Response $response)
    {
        try {

            $body = $request->getBody()->getContents();

            $data = json_decode($body, true);

            $controller = new HistoriasController();

            $historia = $controller->guardarHistoria($data);

            $response->getBody()->write($historia->toJson());

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

    // Actualizar historia
    public function update(Request $request, Response $response, $args)
    {
        try {

            $id = $args['id'];

            $body = $request->getBody()->getContents();

            $data = json_decode($body, true);

            $controller = new HistoriasController();

            $historia = $controller->modificarHistoria($id, $data);

            $response->getBody()->write($historia->toJson());

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

    // Eliminar historia
    public function delete(Request $request, Response $response, $args)
    {
        try {

            $id = $args['id'];

            $controller = new HistoriasController();

            $controller->borrarHistoria($id);

            $response->getBody()->write(json_encode([
                'msg' => 'Historia eliminada correctamente'
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
}