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
use Model\LoginModel;

class ClasseController implements ControllerInterface
{
    public function execute(Request $request): string|null
    {
        $versionAPI = strtoupper($request->getVars()["api"]);

        $sessionDb = (new LoginModel())->getSession($request->getHearder()["X-Token"])["token"];

        if ($versionAPI === "V3"
            && isset($request->getVars()["classe"])
            && $request->getHttpMethod() === "POST"
            && $request->getHearder()["X-Token"] === $sessionDb) {
            try {
                // verifier le fichier
                $fileData = __DIR__ . '/../Data/' . $versionAPI .
                    '/DataSet/classe' . $versionAPI . '-' . $request->getVars()["classe"] . '.json';
                if (file_exists($fileData)
                ) {
                    $classesModel = json_decode(file_get_contents($fileData),true,512,JSON_THROW_ON_ERROR);

                   $classesUser["token"] = $sessionDb;

                   return json_encode(array_replace_recursive($classesModel, $classesUser), JSON_THROW_ON_ERROR);
                }
                return "{message: 'Not access in Id Classe API " . $request->getVars()["classe"] ."'}";

            } catch (\JsonException $e) {
                return "{message: '" . $e->getMessage() . "'}";
            }
        }
        return "{message: 'Bad request to the mock API, Login is available only HTTP POST and API'}";
    }
}
