<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Configuración de base de datos
require __DIR__ . '/../app/Config/database.php';

// Middleware CORS
$cors = require __DIR__ . '/../app/Presentation/Middlewares/CorsMiddleware.php';

// Endpoints de la API
$endpoints = require __DIR__ . '/../app/Presentation/Routers/endpoints.php';

// Crear aplicación Slim
$app = AppFactory::create();

/*
|--------------------------------------------------------------------------
| Middleware global de errores
|--------------------------------------------------------------------------
*/

$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(
    true,   // Mostrar detalles
    true,   // Log errors
    true    // Log error details
);

/*
|--------------------------------------------------------------------------
| Cargar middlewares y rutas
|--------------------------------------------------------------------------
*/

// CORS
$cors($app);

// Endpoints
$endpoints($app);

/*
|--------------------------------------------------------------------------
| Ejecutar aplicación
|--------------------------------------------------------------------------
*/

$app->run();