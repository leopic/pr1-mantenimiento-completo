angular.module('noticiasApp.controllers')
    .controller('FormularioController', ['$scope', '$location', '$routeParams', 'NoticiasService',
        function ($scope, $location, $routeParams, NoticiasService) {
            $scope.init = function() {
                console.debug('Formulario');

                var id = $routeParams.id;
                $scope.formMessages = null;
                $scope.editar = !!id;
                $scope.crear = !$scope.editar;
                $scope.noticia = {
                    titulo: null,
                    contenido: null,
                    url_imagen: null
                };

                if ($scope.editar) {
                    $scope.id = id;
                    cargarNoticia();
                }
            };

            var cargarNoticia = function cargarNoticia() {
                NoticiasService.noticia($scope.id, function(respuesta) {
                    if (respuesta.error) {
                        window.alert(respuesta.message);
                        $location.url('indice');
                    } else {
                        $scope.noticia = respuesta.data;
                    }
                }, function(respuesta) {
                    $scope.error = respuesta.message;
                });
            };

            $scope.procesar = function procesar(esValido) {
                if (esValido) {
                    if ($scope.editar) {
                        editarNoticia();
                    } else {
                        crearNoticia();
                    }
                } else {
                    $scope.formMessages = 'Por favor llene todos los campos.';
                }
            };

            $scope.eliminarNoticia = function eliminarNoticia() {
                if (window.confirm('¿Realmente quiere eliminar ésta noticia?')) {
                    NoticiasService.eliminar($scope.id, function(respuesta) {
                        if (respuesta.error) {
                            $scope.formMessages = respuesta.message;
                        } else {
                            window.alert(respuesta.message);
                            $location.url('indice');
                        }
                    }, function(respuesta) {
                        $scope.formMessages = respuesta.message;
                    });
                }
            };

            var editarNoticia = function editarNoticia() {
                NoticiasService.editar({
                    id: $scope.id,
                    titulo: $scope.noticia.titulo,
                    contenido: $scope.noticia.contenido,
                    url_imagen: $scope.noticia.url_imagen
                }, function(respuesta) {
                    if (respuesta.error) {
                        $scope.formMessages = respuesta.message;
                    } else {
                        window.alert(respuesta.message);
                    }
                }, function(respuesta) {
                    $scope.formMessages = respuesta.message;
                });
            };

            var crearNoticia = function crearNoticia() {
                NoticiasService.crear({
                    titulo: $scope.noticia.titulo,
                    contenido: $scope.noticia.contenido,
                    url_imagen: $scope.noticia.url_imagen
                }, function(respuesta) {
                    if (respuesta.error) {
                        $scope.formMessages = respuesta.message;
                    } else {
                        window.alert(respuesta.message);
                    }
                }, function(respuesta) {
                    $scope.formMessages = respuesta.message;
                });
            };

            $scope.init();
        }]);
