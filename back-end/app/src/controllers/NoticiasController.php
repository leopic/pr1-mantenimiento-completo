<?php

/**
 * NoticiasController.php
 */

namespace App\Controllers;

use App\Services\LoggingService;
use App\Services\NoticiasService;
use Slim\Http\Request;

class NoticiasController {

    private $noticiasService;

    /**
     * NoticiasController constructor.
     */
    public function __construct() {
        $this->noticiasService = new NoticiasService();
    }

    /**
     * @param $request
     * @return array
     */
    public function obtenerLista($request) {
        /** @var Request $request */
        $pagina = $request->getAttribute("pagina", 1);
        return $this->noticiasService->obtenerLista($pagina);
    }

    /**
     * @param $request
     * @return array
     */
    public function obtenerPorId($request) {
        /** @var Request $request */
        $id = $request->getAttribute("id", null);
        return $this->noticiasService->obtenerPorId($id);
    }

    /**
     * @param $request
     * @return array
     */
    public function eliminar($request) {
        /** @var Request $request */
        $id = $request->getAttribute("id", null);
        return $this->noticiasService->eliminar($id);
    }

    /**
     * @param $request
     * @return array
     */
    public function crear($request) {
        /** @var Request $request */
        $formData = $request->getParsedBody();
        $titulo = null;
        $contenido = null;
        $url_imagen = null;

        if (array_key_exists("titulo", $formData)) {
            $titulo = $formData["titulo"];
        }

        if (array_key_exists("contenido", $formData)) {
            $contenido = $formData["contenido"];
        }

        if (array_key_exists("url_imagen", $formData)) {
            $url_imagen = $formData["url_imagen"];
        }

        return $this->noticiasService->crear($titulo, $contenido, $url_imagen);
    }

    public function editar($request) {
        /** @var Request $request */
        $formData = $request->getParsedBody();
        $titulo = null;
        $contenido = null;
        $url_imagen = null;
        $id = null;

        if (array_key_exists("titulo", $formData)) {
            $titulo = $formData["titulo"];
        }

        if (array_key_exists("contenido", $formData)) {
            $contenido = $formData["contenido"];
        }

        if (array_key_exists("url_imagen", $formData)) {
            $url_imagen = $formData["url_imagen"];
        }

        $id = $request->getAttribute("id", null);

        return $this->noticiasService->editar($id, $titulo, $contenido, $url_imagen);
    }

}
