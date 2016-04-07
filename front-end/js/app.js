/**
 * Definición del módulo.
 */
angular.module('noticiasApp', [
        'ngAnimate',
        'ngRoute',
        'ngMessages',
        'noticiasApp.controllers',
        'noticiasApp.directives',
        'noticiasApp.filters',
        'noticiasApp.services'
    ])
    /**
     * Configuración del enrutamiento.
     */
    .config(function($routeProvider) {
        $routeProvider
            .when('/', {
                controller: 'IndiceController',
                templateUrl: 'front-end/partials/indice.html'
            })
            .when('/detalle/:id', {
                controller: 'DetalleController',
                templateUrl: 'front-end/partials/detalle.html'
            })
            .when('/formulario/:id?', {
                controller: 'FormularioController',
                templateUrl: 'front-end/partials/formulario.html'
            })
            .otherwise({
                redirectTo: '/'
            })
    });

// Definimos todos los sub-módulos para evitar conflictos.
angular.module('noticiasApp.controllers', []);
angular.module('noticiasApp.services', []);
angular.module('noticiasApp.directives', []);
angular.module('noticiasApp.filters', []);
