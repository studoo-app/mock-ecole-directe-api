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

class WelcomeController implements \MockEcoleDirecteApi\Core\Controller\ControllerInterface
{

    public function execute(Request $request)
    {
        return "{message: 'Welcome to the mock API'}";
    }
}