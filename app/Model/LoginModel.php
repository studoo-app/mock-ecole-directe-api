<?php
/*
 * Ce fichier fait partie du mock-ecole-directe-api.
 *
 * (c) redbull
 *
 * Pour les informations complètes sur les droits d'auteur et la licence,
 * veuillez consulter le fichier LICENSE qui a été distribué avec ce code source.
 */


namespace app\Model;

use app\Core\DatabaseService;

class LoginModel
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseService::getConnect();
    }

    public function addSession(string $token, string $login): void
    {
        $stmt = $this->db->prepare("INSERT INTO users (token, login) VALUES (:token, :login)");
        $stmt->execute(['token' => $token, 'login' => $login]);
    }

    public function getSession(string $token): mixed
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }
}