<?php

/**
 * PersistenciaService.php
 * Interacción con la base de datos.
 */

namespace App\Services;

use \PDO;
use \PDOException;

class PersistenciaService {

    // Instancia de la conexión a la BD, usada internamente.
    private $pdo;

    public function __construct() {
        // Incluimos el archivo que contiene las credenciales
        require("bd-credenciales.php");

        // Creamos una nueva conexión.
        $this->pdo = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name']}",
            $config['db_user'], $config['db_pass']
        );

        // Le solicitamos a la conexión que nos notifique de todos los errores.
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /**
         * Sin la siguiente query, tendremos errores en la paginación, ya que PDO al usar `execute` asume que
         * nuestros parámetros son string.
         */
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    }

    /**
     * Ejecuta un query de SQL.
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function query($query, $params=[]) {
        $cuentaDeRegistrosAfectados = null;

        /**
         * Creamos un diccionario en donde se almacenará el resultado de la operación.
         * Los datos en sí, se regresarán bajo la llave `data` del diccionario, iniciada en null
         */
        $result = [
            "data" => null
        ];

        $isInsert = $this->esInsert($query);
        $isDelete = $this->esDelete($query);
        $isUpdate = $this->esUpdate($query);
        $isSelect = $this->esSelect($query);

        try {
            // Preparamos la query a ejecutar
            $stmt = $this->pdo->prepare($query);

            if ($isDelete) {
                $finalQuery = $query;

                /**
                 * El método `exec`, usado para obtener un conteo al borrar entradas, únicamente acepta strings a
                 * ejectuar, por lo que debemos remplazar las variables manualmente
                 */
                foreach ($params as $key => $value) {
                    if (is_int($value)) {
                        $finalQuery = str_replace($key, $value, $finalQuery);
                    } else {
                        $finalQuery = str_replace($key, "'$value'", $finalQuery);
                    }
                }

                $cuentaDeRegistrosAfectados = $this->pdo->exec($finalQuery);
            } else {
                $stmt->execute($params);
            }

            if ($isSelect) {
                // Vaciamos el resultado dentro de `data`
                while ($content = $stmt->fetch()) {
                    $result["data"][] = $content;
                }

                // El total de registros lo brinda el resultado
                $cuentaDeRegistrosAfectados = count($result["data"]);
            }

            if ($isInsert) {
                // Junto con el ID del elemento agregado
                $result["meta"]["id"] = $this->pdo->lastInsertId();
            }

            if ($isUpdate || $isInsert) {
                // Retornamos la cantidad de elementos afectados
                $cuentaDeRegistrosAfectados = $stmt->rowCount();
            }
        } catch (PDOException $e) {
            // En caso de que algo saliera mal con nuestro intento de conexión, el mensaje se envia de vuelta al
            // servicio que consumió este método.
            $result["error"] = true;
            $result["message"] = $e->getMessage();
        }

        if (isset($cuentaDeRegistrosAfectados)) {
            $result["meta"]["count"] = $cuentaDeRegistrosAfectados;
        }

        return $result;
    }

    /**
     * Revisamos si la query busca leer datos de la BD.
     *
     * @param string $query
     * @return bool
     */
    private function esSelect($query) {
        return $this->revisarTipoDeQuery($query, "SELECT");
    }

    /**
     * Revisamos si la query busca agregar datos a la BD.
     *
     * @param string $query
     * @return bool
     */
    private function esInsert($query) {
        return $this->revisarTipoDeQuery($query, "INSERT");
    }

    /**
     * Revisamos si la query busca actualizar datos en la BD.
     *
     * @param string $query
     * @return bool
     */
    private function esUpdate($query) {
        return $this->revisarTipoDeQuery($query, "UPDATE");
    }

    /**
     * Revisamos si la query busca eliminar datos de la BD.
     *
     * @param string $query
     * @return bool
     */
    private function esDelete($query) {
        return $this->revisarTipoDeQuery($query, "DELETE");
    }

    /**
     * Revisión genérica de tipo de query
     *
     * @param string $query
     * @param string $tipo
     * @return bool
     */
    private function revisarTipoDeQuery($query, $tipo) {
        return substr_count(strtoupper($query), $tipo) > 0;
    }

}
