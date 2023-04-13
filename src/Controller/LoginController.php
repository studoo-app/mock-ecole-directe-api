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

class LoginController implements \MockEcoleDirecteApi\Core\Controller\ControllerInterface
{

    /**
     * @throws \JsonException
     */
    public function execute(Request $request): bool|string
    {
        if ($request->getVars()["api"] === "v3" && $request->getHttpMethod() === "POST") {
            try {
                $versionAPI = strtoupper($request->getVars()["api"]);
                $loginJson = json_decode(
                    file_get_contents(__DIR__ . '/../Data/loginDataset.json'),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                foreach ($loginJson["login"][$request->getVars()["api"]] as $login) {
                    if ($login["identifiant"] === $request->getVars()["identifiant"]
                        && $login["motdepasse"] === $request->getVars()["motdepasse"]) {

                        $dataModelJson = json_decode(
                            file_get_contents(__DIR__ .
                                '/../Data/' . $versionAPI .
                                '/Model/login' . $versionAPI .
                                'Type' . $login["typeCompte"] .
                                '.json'),
                            true,
                            512,
                            JSON_THROW_ON_ERROR
                        );

                        $dataUserJson = json_decode(
                            file_get_contents(__DIR__ .
                                '/../Data/' . $versionAPI .
                                '/DataSet/login' . $versionAPI .
                                'Type' . $login["typeCompte"] .
                                '-' . $login["identifiant"] .
                                '.json'),
                            true,
                            512,
                            JSON_THROW_ON_ERROR
                        );

                        return json_encode(array_replace_recursive($dataModelJson, $dataUserJson), JSON_THROW_ON_ERROR);
                    }
                }
                return "{message: 'Login/Password is not available in API " . $versionAPI ."'}";
            } catch (\JsonException $e) {
                return "{message: '" . $e->getMessage() . "'}";
            }
        }

        return "{message: 'Bad request to the mock API, Login is available only HTTP POST and API'}";
    }
}
