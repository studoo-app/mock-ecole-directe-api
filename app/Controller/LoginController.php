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
use Core\TokenHandler;
use Model\LoginModel;
use PDO;
use Studoo\EduFramework\Core\Controller\ControllerInterface;
use Studoo\EduFramework\Core\Controller\Request;

class LoginController implements ControllerInterface
{
    public function execute(Request $request): string|null
    {
        if ($request->getVars()["api"] === "v3" && $request->getHttpMethod() === "POST") {
            try {

                // Pour récupérer des données de type text/plain
                if ($_SERVER['REQUEST_METHOD'] === 'POST'
                    && isset($_SERVER['CONTENT_TYPE'])
                    && $_SERVER['CONTENT_TYPE'] === 'text/plain') {
                    // Lire les données brutes de la requête
                    $postTextPlain = json_decode(
                        str_replace(
                            "data=",
                            "",
                            file_get_contents('php://input')
                        ),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    );
                }

                $db = DatabaseService::getConnect();
                $versionAPI = strtoupper($request->getVars()["api"]);
                $stmt = $db->prepare("SELECT * FROM users WHERE login = :login AND password = :password");
                $stmt->bindParam(':login', $postTextPlain["identifiant"]);
                $stmt->bindParam(':password', $postTextPlain["motdepasse"]);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

               if ($user["login"] === $postTextPlain["identifiant"] && $user["password"] === $postTextPlain["motdepasse"]) {

                   $dataModelJson = json_decode(
                       file_get_contents(__DIR__ .
                           '/../Data/' . $versionAPI .
                           '/Model/login' . $versionAPI .
                           'Type' . $user["typeCompte"] .
                           '.json'),
                       true,
                       512,
                       JSON_THROW_ON_ERROR
                   );


                   $dataModelJson["data"]["accounts"][0]["identifiant"] = $user["login"];
                   var_dump($dataModelJson);


                   $dataModelJson["token"] = TokenHandler::generate(26);
                   $login = json_encode(
                        $dataModelJson,
                       JSON_THROW_ON_ERROR
                   );

                   (new LoginModel())->addSession($dataModelJson["token"], $login);

                   return $login;
               }


/*                foreach ($loginJson["login"][$request->getVars()["api"]] as $login) {
                    // Check Login
                    if ($login["identifiant"] === $postTextPlain["identifiant"]
                        && $login["motdepasse"] === $postTextPlain["motdepasse"]) {

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

                        $dataUserJson["token"] = TokenHandler::generate(26);
                        $login = json_encode(
                            array_replace_recursive($dataModelJson, $dataUserJson),
                            JSON_THROW_ON_ERROR
                        );

                        (new LoginModel())->addSession($dataUserJson["token"], $login);

                        return $login;
                    }
                }*/


                return "{message: 'Login/Password is not available in API " . $versionAPI ."'}";
            } catch (\JsonException|\Exception $e) {
                return "{message: '" . $e->getMessage() . "'}";
            }
        }

        return "{message: 'Bad request to the mock API, Login is available only HTTP POST and API'}";
    }
}
