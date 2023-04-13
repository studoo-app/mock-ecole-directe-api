<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MockEcoleDirecteApi\Core\Controller\FastRouteCore;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', 'MockEcoleDirecteApi\Controller\WelcomeController');
    $route->addRoute(['GET','POST'], '/{api}/login', 'MockEcoleDirecteApi\Controller\LoginController');
});

echo FastRouteCore::getDispatcher($dispatcher);
