<?php
/*
 * Ce fichier fait partie du mock-ecole-directe-api.
 *
 * (c) redbull
 *
 * Pour les informations complètes sur les droits d'auteur et la licence,
 * veuillez consulter le fichier LICENSE qui a été distribué avec ce code source.
 */


namespace Controller;

use app\Core\Controller\Request;
use app\Core\DatabaseService;

class InitController implements \app\Core\Controller\ControllerInterface
{
    public function execute(Request $request): bool|string
    {
        $db = DatabaseService::getConnect();

        $db->exec("CREATE TABLE IF NOT EXISTS users (
                                id INTEGER PRIMARY KEY,
                                token TEXT,
                                login TEXT,
                                tokenExpiration DATETIME DEFAULT CURRENT_TIMESTAMP
                                )
                            ");

        return "{message: 'initialisation the mock API Ecole Directe'}";
    }
}
