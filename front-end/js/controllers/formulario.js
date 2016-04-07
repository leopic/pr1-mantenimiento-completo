angular.module('noticiasApp.controllers')
    .controller('FormularioController', ['$scope', '$location', '$routeParams', 'NoticiasService',
        function ($scope, $location, $routeParams, NoticiasService) {
            $scope.init = function() {
                console.debug('Formulario');

                $scope.formMessages = null;

                $scope.noticia = {
                    titulo: null,
                    contenido: null,
                    url_imagen: null
                };

                $scope.editar = !!$routeParams.id;
                $scope.crear = !$scope.editar;

                if ($scope.editar) {
                    $scope.id = $routeParams.id;
                    cargarNoticia();
                }
            };

            var cargarNoticia = function cargarNoticia() {
                NoticiasService.noticia($scope.id, function(respuesta) {
                    if (respuesta.error) {
                        $scope.error = respuesta.message;
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
                    } else {
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
                    }
                } else {
                    $scope.formMessages = 'Por favor llene todos los campos.';
                }
            };

            $scope.eliminar = function eliminar() {
                if (window.confirm('Realmente quiere eliminar esta noticia?')) {
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

            $scope.init();
        }]);
