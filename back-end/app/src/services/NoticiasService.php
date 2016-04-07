<?php

/**
 * NoticiasService.php
 */

namespace App\Services;

class NoticiasService {

    private $storage;
    private $validation;

    /**
     * NoticiasService constructor.
     */
    public function __construct() {
        $this->storage = new PersistenciaService();
        $this->validation = new ValidacionesService();
    }

    public function obtenerPorId($id) {
        $respuesta = [];

        if ($this->validation->isValidInt($id)) {
            // El query que vamos a ejecutar en la BD
            $query = "SELECT titulo, url, contenido, url_imagen FROM noticias WHERE id = :id LIMIT 1";

            // Los parámetros de ese query
            $parametros = [":id" => intval($id)];

            // El resultado de de ejecutar la sentencia se almacena en la variable `resultado`
            $resultadoDelQuery = $this->storage->query($query, $parametros);

            // Si la setencia tiene por lo menos una fila, quiere decir que encontramos nuestra noticia
            $seEncontroLaNoticia = array_key_exists("meta", $resultadoDelQuery) &&
                $resultadoDelQuery["meta"]["count"] == 1;

            if ($seEncontroLaNoticia) {
                $respuesta["message"] = "Noticia encontrada.";
                $noticia = $resultadoDelQuery["data"][0];
                $respuesta["data"] = [
                    "id" => $id,
                    "titulo" => $noticia["titulo"],
                    "contenido" => $noticia["contenido"],
                    "url" => $noticia["url"],
                    "url_imagen" => $noticia["url_imagen"]
                ];
            } else {
                $respuesta["message"] = "Imposible encontrar noticia con el id $id.";
                $respuesta["error"] = true;
            }
        } else {
            $respuesta["message"] = "El campo id es requerido.";
            $respuesta["error"] = true;
        }

        return $respuesta;
    }

    public function obtenerLista($pagina = 1) {
        $respuesta = [];
        $tamanoPagina = 20;
        $pagina = $pagina == 0 ? 1 : intval($pagina);
        $desplace = ($pagina - 1) * $tamanoPagina;

        $query = "SELECT id, titulo, url, contenido, url_imagen FROM noticias LIMIT :tamanoPagina OFFSET :desplace";
        $parametros = [":tamanoPagina" => $tamanoPagina, ":desplace" => $desplace];
        $resultadoDelQuery = $this->storage->query($query, $parametros);
        $seEncontraronLasNoticias = array_key_exists("meta", $resultadoDelQuery) &&
            $resultadoDelQuery["meta"]["count"] > 0;

        if ($seEncontraronLasNoticias) {
            $respuesta["message"] = "Noticias encontradas satisfactoriamente.";
            $noticias = $resultadoDelQuery["data"];

            foreach ($noticias as $noticia) {
                $respuesta["data"][] = [
                    "id" => $noticia["id"],
                    "titulo" => $noticia["titulo"],
                    "url" => $noticia["url"],
                    "url_imagen" => $noticia["url_imagen"],
                ];
            }

            $cuentaEnEstaPagina = $resultadoDelQuery["meta"]["count"];
            $cuentaTotal = $this->obtenerTotal();

            $respuesta["meta"] = [
                "noticiasEnEstaPagina"  => $cuentaEnEstaPagina,
                "noticiasPorPagina"     => $tamanoPagina,
                "numeroDePaginas"       => ceil($cuentaTotal / $tamanoPagina),
                "paginaActual"          => $pagina,
                "totalDeNoticias"       => $cuentaTotal
            ];
        } else {
            $respuesta["message"] = "No se encontraron noticias";
            $respuesta["error"] = true;
        }

        return $respuesta;
    }

    private function obtenerTotal() {
        $query = "SELECT COUNT(*) AS total FROM noticias";
        $resultadoDelQuery = $this->storage->query($query);
        return $resultadoDelQuery["data"][0]["total"];
    }

    public function crear($titulo, $contenido, $urlImagen) {
        $respuesta = [];

        if ($this->validation->isValidString($titulo)) {
            if ($this->validation->isValidString($contenido)) {
                if ($this->validation->isValidString($urlImagen)) {
                    $query = "INSERT INTO noticias (titulo, contenido, url, url_imagen) VALUES (:titulo, :contenido, :url, :url_imagen)";
                    $parametros = [
                        ":titulo" => $titulo,
                        ":contenido" => $contenido,
                        ":url" => $this->crearURL($titulo),
                        ":url_imagen" => $urlImagen,
                    ];
                    $resultadoDelQuery = $this->storage->query($query, $parametros);
                    $seCreoLaNoticia = array_key_exists("meta", $resultadoDelQuery) && $resultadoDelQuery["meta"]["count"] == 1;

                    if ($seCreoLaNoticia) {
                        $respuesta["message"] = "Noticia creada exitosamente";
                        $respuesta["meta"]["id"] = $resultadoDelQuery["meta"]["id"];
                    } else {
                        $respuesta["error"] = true;
                        $respuesta["message"] = "Error creando noticia";
                    }
                } else {
                    $respuesta["error"] = true;
                    $respuesta["message"] = "El URL de la imagen es invalida";
                }
            } else {
                $respuesta["error"] = true;
                $respuesta["message"] = "Contenido invalido";
            }
        } else {
            $respuesta["error"] = true;
            $respuesta["message"] = "Titulo invalido";
        }

        return $respuesta;
    }

    public function editar($id, $titulo, $contenido, $urlImagen) {
        $respuesta = [];

        if ($this->validation->isValidString($titulo)) {
            if ($this->validation->isValidString($contenido)) {
                if ($this->validation->isValidString($urlImagen)) {
                    if ($this->validation->isValidInt($id)) {
                        $query = "
                                  UPDATE noticias SET titulo = :titulo,
                                                      contenido = :contenido,
                                                      url = :url,
                                                      url_imagen = :url_imagen
                                  WHERE id = :id
                                ";
                        $parametros = [
                            ":titulo" => $titulo,
                            ":contenido" => $contenido,
                            ":url" => $this->crearURL($titulo),
                            ":url_imagen" => $urlImagen,
                            ":id" => $id,
                        ];
                        $resultadoDelQuery = $this->storage->query($query, $parametros);
                        $seEditoLaNoticia = array_key_exists("meta", $resultadoDelQuery) && $resultadoDelQuery["meta"]["count"] == 1;

                        if ($seEditoLaNoticia) {
                            $respuesta["message"] = "Noticia actualizada exitosamente";
                        } else {
                            $respuesta["error"] = true;
                            $respuesta["message"] = "Error actualizando noticia";
                        }
                    } else {
                        $respuesta["error"] = true;
                        $respuesta["message"] = "El ID es inválido.";
                    }
                } else {
                    $respuesta["error"] = true;
                    $respuesta["message"] = "El URL de la imagen es invalida";
                }
            } else {
                $respuesta["error"] = true;
                $respuesta["message"] = "Contenido invalido";
            }
        } else {
            $respuesta["error"] = true;
            $respuesta["message"] = "Titulo invalido";
        }

        return $respuesta;
    }

    public function eliminar($id) {
        $respuesta = [];

        if ($this->validation->isValidInt($id)) {
            $id = intval($id);
            $query = "DELETE FROM noticias WHERE id = :id";
            $parametros = [":id" => $id];
            $resultadoDelQuery = $this->storage->query($query, $parametros);
            $seEliminoLaNoticia = array_key_exists("meta", $resultadoDelQuery) && $resultadoDelQuery["meta"]["count"] == 1;

            if ($seEliminoLaNoticia) {
                $respuesta["message"] = "Noticia eliminada.";
            } else {
                $respuesta["message"] = "Imposible encontrar noticia con el id $id.";
                $respuesta["error"] = true;
            }
        } else {
            $respuesta["message"] = "El campo id es requerido.";
            $respuesta["error"] = true;
        }

        return $respuesta;
    }

    // TODO: mejorar
    /**
     * @param string $titulo
     * @return string
     */
    private function crearURL($titulo) {
        $slug = str_replace(" ", "-", $titulo);
        return $slug;
    }

}
