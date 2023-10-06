<?php
require_once __DIR__ . '/../vendor/autoload.php';

use app\Core\Controller\FastRouteCore;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', 'Controller\WelcomeController');
    $route->addRoute('GET', '/init', 'Controller\InitController');
    $route->addRoute(['GET','POST'], '/{api}/login', 'Controller\LoginController');
    $route->addRoute(['GET','POST'], '/{api}/classes/{classe}/eleves', 'Controller\ClasseController');
});

header("Content-type: application/json");
header("x-powered-by: ASP.NET");
header("x-http-host: HTTP84");
header("access-control-allow-origin: *");
echo FastRouteCore::getDispatcher($dispatcher);
