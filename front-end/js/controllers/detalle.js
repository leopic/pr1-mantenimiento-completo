angular.module('noticiasApp.controllers')
    .controller('DetalleController', ['$scope', '$routeParams', 'NoticiasService',
        function ($scope, $routeParams, NoticiasService) {
            $scope.init = function() {
                console.debug('Detalle');

                $scope.noticia = null;
                if ($routeParams.id) {
                    $scope.id = $routeParams.id;
                    cargarNoticia();
                } else {
                    $scope.error = 'Noticia no encontrada';
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

            $scope.init();
        }]);
