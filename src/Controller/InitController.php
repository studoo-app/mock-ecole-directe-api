<?php
/*
 * Ce fichier fait partie du mock-ecole-directe-api.
 *
 * (c) redbull
 *
 * Pour les informations complètes sur les droits d'auteur et la licence,
 * veuillez consulter le fichier LICENSE qui a été distribué avec ce code source.
 */


namespace MockEcoleDirecteApi\Controller;

use MockEcoleDirecteApi\Core\Controller\Request;
use MockEcoleDirecteApi\Core\DatabaseService;

class InitController implements \MockEcoleDirecteApi\Core\Controller\ControllerInterface
{
    public function execute(Request $request): bool|string
    {
        $db = DatabaseService::getConnect();

        $db->exec("CREATE TABLE IF NOT EXISTS users (
                                id INTEGER PRIMARY KEY,
                                token TEXT,
                                login TEXT
                                )
                            ");

        return "{message: 'initialisation the mock API Ecole Directe'}";
    }
}
