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

use Studoo\EduFramework\Core\Controller\ControllerInterface;
use Studoo\EduFramework\Core\Controller\Request;
use Core\DatabaseService;

class InitController implements ControllerInterface
{
    public function execute(Request $request): string|null
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
