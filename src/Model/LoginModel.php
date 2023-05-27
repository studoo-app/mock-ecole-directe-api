<?php
/*
 * Ce fichier fait partie du mock-ecole-directe-api.
 *
 * (c) redbull
 *
 * Pour les informations complètes sur les droits d'auteur et la licence,
 * veuillez consulter le fichier LICENSE qui a été distribué avec ce code source.
 */


namespace MockEcoleDirecteApi\Model;

use MockEcoleDirecteApi\Core\DatabaseService;

class LoginModel
{
    public function addSession(string $token, string $login): void
    {
        $db = DatabaseService::getConnect();
        $stmt = $db->prepare("INSERT INTO users (token, login) VALUES (:token, :login)");
        $stmt->execute(['token' => $token, 'login' => $login]);
    }


}