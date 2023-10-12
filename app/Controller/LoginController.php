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

use Core\ConfigDataSet;
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

                   $dataSetConfig = ConfigDataSet::get(__DIR__ . '/../../var/configDataset.json');

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

                   $dataModelJson["token"] = TokenHandler::generate(18);
                   (new LoginModel())->addSession($dataModelJson["token"], $user["login"]);

                   $dataModelJson["data"]["accounts"][0]["identifiant"] = $user["login"];
                   $dataModelJson["data"]["accounts"][0]["civilite"] = ($user["sexe"] === "F") ? "Mme." : "M.";
                   $dataModelJson["data"]["accounts"][0]["nom"] = $user["nom"];
                   $dataModelJson["data"]["accounts"][0]["prenom"] = $user["prenom"];
                   $dataModelJson["data"]["accounts"][0]["typeCompte"] = $user["typeCompte"];
                   $dataModelJson["data"]["accounts"][0]["lastConnexion"] = $user["lastConnexion"];
                   $dataModelJson["data"]["accounts"][0]["idLogin"] = $user["idLogin"];
                   $dataModelJson["data"]["accounts"][0]["id"] = $user["id"];
                   $dataModelJson["data"]["accounts"][0]["email"] = $user["email"];
                   $dataModelJson["data"]["accounts"][0]["uid"] = $user["uid"];
                   $dataModelJson["data"]["accounts"][0]["socketToken"] = TokenHandler::generate(40);
                   $dataModelJson["data"]["accounts"][0]["codeOgec"] = $dataSetConfig["organisation"]["codeOgec"];
                   $dataModelJson["data"]["accounts"][0]["nomEtablissement"] = $dataSetConfig["organisation"]["nomEtablissement"];

                   $dataModelJson["data"]["accounts"][0]["profile"]["nomEtablissement"] = $dataSetConfig["organisation"]["nomEtablissement"];
                   $dataModelJson["data"]["accounts"][0]["profile"]["idEtablissement"] = $dataSetConfig["organisation"]["codeOgec"];
                   $dataModelJson["data"]["accounts"][0]["profile"]["telPortable"] = $user["telPortable"];

                   if ($user["typeCompte"] === "P") {
                       $dataModelJson["data"]["accounts"][0]["profile"]["email"] = $user["email"];
                       $dataModelJson["data"]["accounts"][0]["profile"]["classes"] = json_decode($user["classeId"], false, 512, JSON_THROW_ON_ERROR);
                   }

                   if ($user["typeCompte"] === "E") {
                       $dataModelJson["data"]["accounts"][0]["profile"]["sexe"] = $user["sexe"];
                       $dataModelJson["data"]["accounts"][0]["profile"]["rneEtablissement"] = $dataSetConfig["organisation"]["codeOgec"];
                       $dataModelJson["data"]["accounts"][0]["profile"]["classe"] = json_decode($user["classeId"], false, 512, JSON_THROW_ON_ERROR);
                   }

                   // encodage du json du User
                   $login = json_encode(
                        $dataModelJson,
                       JSON_THROW_ON_ERROR
                   );

                   return $login;
               }

               return "{message: 'Login/Password is not available in API " . $versionAPI ."'}";
            } catch (\JsonException|\Exception $e) {
                return "{message: '" . $e->getMessage() . "'}";
            }
        }

        return "{message: 'Bad request to the mock API, Login is available only HTTP POST and API'}";
    }
}
