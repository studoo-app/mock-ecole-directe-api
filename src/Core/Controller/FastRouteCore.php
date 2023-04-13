<?php

namespace MockEcoleDirecteApi\Core\Controller;

use FastRoute\Dispatcher;

class FastRouteCore
{
    /**
     * @throws \JsonException
     */
    public static function getDispatcher($dispatcher)
    {

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $request = new Request($uri, $httpMethod);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        // Pour récupérer des données de type text/plain
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_SERVER['CONTENT_TYPE'])
            && $_SERVER['CONTENT_TYPE'] === 'text/plain') {
            // Lire les données brutes de la requête
            $routeInfo[2] = array_merge(
                $routeInfo[2],
                json_decode(
                    str_replace(
                        "data=",
                        "",
                        file_get_contents('php://input')),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                )
            );
        }

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                header('HTTP/1.1 404 Bad Request');
                echo "HTTP/1.1 404 Not Found Request";
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                header('HTTP/1.1 400 Bad Request');
                echo "HTTP/1.1 400 Bad Request";
                break;
            case Dispatcher::FOUND:
                $request->setHander($routeInfo[1])->setVars($routeInfo[2]);
                $handler = $request->getHander();
                $exeController = new $handler(); // -> Creation du controller correspondant à la demande
                return $exeController->execute($request);
        }
    }
}
