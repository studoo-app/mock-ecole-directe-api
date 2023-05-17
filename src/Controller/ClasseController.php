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

    /**
     * @throws \JsonException
     */
    public function execute(Request $request): bool|string
    {
        $versionAPI = strtoupper($request->getVars()["api"]);
        if ($versionAPI === "V3" && isset($request->getVars()["id"]) && $request->getHttpMethod() === "POST") {
            try {

                // verifier le fichier
                if (file_exists(__DIR__ . '/../Data/'.$versionAPI.'/DataSet/classe'.$versionAPI.'-'.$request->getVars()["id"].'.json')) {
                    $classesModel = json_decode(
                        file_get_contents(__DIR__ . '/../Data/'.$versionAPI.'/DataSet/classe'.$versionAPI.'-'.$request->getVars()["id"].'.json'),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    );
                    return json_encode($classesModel, JSON_THROW_ON_ERROR);
                } else {
                    return "{message: 'Not access in Id Classe API " . $request->getVars()["id"] ."'}";
                }

                return "{message: 'Id is not available in API " . $versionAPI ."'}";
            } catch (\JsonException $e) {
                return "{message: '" . $e->getMessage() . "'}";
            }
        }

        return "{message: 'Bad request to the mock API, Login is available only HTTP POST and API'}";
    }
}
