<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MockEcoleDirecteApi\Core\Controller\FastRouteCore;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', 'MockEcoleDirecteApi\Controller\WelcomeController');
    $route->addRoute('GET', '/init', 'MockEcoleDirecteApi\Controller\InitController');
    $route->addRoute(['GET','POST'], '/{api}/login', 'MockEcoleDirecteApi\Controller\LoginController');
    $route->addRoute(['GET','POST'], '/{api}/classes/{classe}/eleves', 'MockEcoleDirecteApi\Controller\ClasseController');
});

header("Content-type: application/json");
header("x-powered-by: ASP.NET");
header("x-http-host: HTTP84");
header("access-control-allow-origin: *");
echo FastRouteCore::getDispatcher($dispatcher);
