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

class ClasseController implements \MockEcoleDirecteApi\Core\Controller\ControllerInterface
{
    public function execute(Request $request): bool|string
    {
        $versionAPI = strtoupper($request->getVars()["api"]);

        if ($versionAPI === "V3"
            && isset($request->getVars()["classe"])
            && $request->getHttpMethod() === "POST"
            && $request->getHearder()["X-Token"] === $_SESSION["token"]) {
            try {
                // verifier le fichier
                $fileData = __DIR__ . '/../Data/' . $versionAPI .
                    '/DataSet/classe' . $versionAPI . '-' . $request->getVars()["classe"] . '.json';
                if (file_exists($fileData)
                ) {
                    $classesModel = json_decode(file_get_contents($fileData),true,512,JSON_THROW_ON_ERROR);

                   $classesUser["token"] = $_SESSION["token"];

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
