angular.module('noticiasApp.services')
    /**
     * Encargado de todas las operaciones relacionadas con los usuarios.
     */
    .service('NoticiasService', ['$http', function ($http) {
        var noticias = function noticias(pagina, exito, error) {
            var url = 'back-end/noticias/' + pagina;

            return $http.get(url).then(function(respuesta) {
                if (respuesta.data.error) {
                    console.debug('lista.servicio: error');
                    error(respuesta.data);
                } else {
                    console.debug('lista.servicio: éxito');
                    exito(respuesta.data);
                }
            }, function(response) {
                console.debug('lista.servicio: error');
                error(response.data);
            });
        };

        var noticia = function noticia(id, exito, error) {
            var url = 'back-end/noticia/' + id;

            return $http.get(url).then(function(respuesta) {
                if (respuesta.data.error) {
                    console.debug('noticia.servicio: error');
                    error(respuesta.data);
                } else {
                    console.debug('noticia.servicio: éxito');
                    exito(respuesta.data);
                }
            }, function(response) {
                console.debug('individual.servicio: error');
                error(response.data);
            });
        };

        var crear = function crear(noticia, exito, error) {
            var url = 'back-end/noticia/crear',
                datos = {
                    titulo: noticia.titulo,
                    contenido: noticia.contenido,
                    url_imagen: noticia.url_imagen
                };

            return $http.post(url, datos).then(function(respuesta) {
                if (respuesta.data.error) {
                    console.debug('crear.servicio: error');
                    error(respuesta.data);
                } else {
                    console.debug('crear.servicio: éxito');
                    exito(respuesta.data);
                }
            }, function(response) {
                console.debug('crear.servicio: error');
                error(response.data);
            });
        };

        var editar = function editar(noticia, exito, error) {
            var url = 'back-end/noticia/editar/' + noticia.id,
                datos = {
                    titulo: noticia.titulo,
                    contenido: noticia.contenido,
                    url_imagen: noticia.url_imagen
                };

            return $http.post(url, datos).then(function(respuesta) {
                if (respuesta.data.error) {
                    console.debug('editar.servicio: error');
                    error(respuesta.data);
                } else {
                    console.debug('editar.servicio: éxito');
                    exito(respuesta.data);
                }
            }, function(response) {
                console.debug('editar.servicio: error');
                error(response.data);
            });
        };

        var eliminar = function eliminar(id, exito, error) {
            var url = 'back-end/noticia/eliminar/' + id;

            return $http.get(url).then(function(respuesta) {
                if (respuesta.data.error) {
                    console.debug('eliminar.servicio: error');
                    error(respuesta.data);
                } else {
                    console.debug('eliminar.servicio: éxito');
                    exito(respuesta.data);
                }
            }, function(response) {
                console.debug('eliminar.servicio: error');
                error(response.data);
            });
        };

        return {
            noticias: noticias,
            noticia: noticia,
            crear: crear,
            editar: editar,
            eliminar: eliminar
        };
    }]);
