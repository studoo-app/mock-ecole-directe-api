<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MockEcoleDirecteApi\Core\Controller\FastRouteCore;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', 'MockEcoleDirecteApi\Controller\WelcomeController');
    $route->addRoute(['GET','POST'], '/{api}/login', 'MockEcoleDirecteApi\Controller\LoginController');
});

header("Content-type: application/json");
header("x-powered-by: ASP.NET");
header("x-http-host: HTTP84");
header("access-control-allow-origin: *");
echo FastRouteCore::getDispatcher($dispatcher);
