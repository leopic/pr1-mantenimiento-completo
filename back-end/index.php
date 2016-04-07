<?php

/**
 * index.php
 * Inicia la aplicación y sirve como enrutador para el back-end.
 */

require "bootstrap.php";

//use App\Controllers\NoticiasController;
use Slim\Http\Request;
use Slim\Http\Response;

$app = new \Slim\App();

// Definimos nuestras rutas

// Lista de noticias
$app->get(
    "/noticias[/{pagina}]",
    function ($request, $response) {
        /** @var Response $response */
        $controlador = new App\Controllers\NoticiasController();
        $resultado = $controlador->obtenerLista($request);
        return $response->withJson($resultado);
    }
);

// Una entrada específica
$app->get(
    "/noticia/{id}",
    function ($request, $response) {
        /** @var Response $response */
        $controlador = new App\Controllers\NoticiasController();
        $resultado = $controlador->obtenerPorId($request);
        return $response->withJson($resultado);
    }
);

// Crear nuevas noticias
$app->post(
    "/noticia/crear",
    function ($request, $response) {
        /** @var Response $response */
        $controlador = new App\Controllers\NoticiasController();
        $resultado = $controlador->crear($request);
        return $response->withJson($resultado);
    }
);

// Editar una noticia
$app->post(
    "/noticia/editar/{id}",
    function ($request, $response) {
        /** @var Response $response */
        $controlador = new App\Controllers\NoticiasController();
        $resultado = $controlador->editar($request);
        return $response->withJson($resultado);
    }
);

// Eliminar una noticia
$app->get(
    "/noticia/eliminar/{id}",
    function ($request, $response) {
        /** @var Response $response */
        $controlador = new App\Controllers\NoticiasController();
        $result = $controlador->eliminar($request);
        return $response->withJson($result);
    }
);

// Corremos la aplicación.
$app->run();
