<?php

/**
 * index.php
 * HTML desplegado al usuario.
 */

// Lista de archivos de JavaScript a cargar en la página.
$scripts = [
    'lib/angular.min.js',
    'lib/angular-messages.min.js',
    'lib/angular-route.min.js',
    'lib/angular-animate.min.js',
    'app.js',
    'services/noticias.js',
    'controllers/indice.js',
    'controllers/detalle.js',
    'controllers/formulario.js'
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proyecto Uno: Ejemplo Mantenimiento</title>
    <link rel="stylesheet" href="front-end/css/bootstrap.min.css">
</head>
<body>

<div class="col-md-4 col-md-offset-4">
    <div ng-app="noticiasApp" ng-view></div>
</div>

<?php
// Carga de los archivos de JavaScript
foreach ($scripts as $script) {
    echo "<script src='front-end/js/$script'></script> \n";
}
?>
</body>
</html>
