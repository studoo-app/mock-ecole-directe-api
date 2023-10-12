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

use Core\DatabaseService;
use PDO;
use Studoo\EduFramework\Core\Controller\ControllerInterface;
use Studoo\EduFramework\Core\Controller\Request;

class DataSetEtudiantsController implements ControllerInterface
{

    public function execute(Request $request): string|null
    {
        $db = DatabaseService::getConnect();
        $stmt = $db->prepare("SELECT * FROM users WHERE typeCompte = 'E' ORDER BY classeId");
        $stmt->execute();

        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_THROW_ON_ERROR);
    }
}