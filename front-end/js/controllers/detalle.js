angular.module('noticiasApp.controllers')
    /**
     * Inicia la sesión del usuario en el sistema.
     */
    .controller('DetalleController', ['$scope', '$routeParams', 'NoticiasService',
        function ($scope, $routeParams, NoticiasService) {
            $scope.init = function() {
                console.debug('Detalle');
                $scope.noticia = null;
                $scope.error = null;
                $scope.id = $routeParams.id;
                cargarNoticia();
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
