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

use Core\StandardRaw;
use Core\TokenHandler;
use Studoo\EduFramework\Core\Controller\ControllerInterface;
use Studoo\EduFramework\Core\Controller\Request;
use Core\DatabaseService;

class InitController implements ControllerInterface
{
    /**
     * @throws \JsonException
     */
    public function execute(Request $request): string|null
    {
        if (file_exists(__DIR__ . '/../../var/ecoledirecte.db')) {
            unlink(__DIR__ . '/../../var/ecoledirecte.db');
        }

        ##<< Insertion des conf dataset
        if (file_exists(__DIR__ . '/../../var/configDataset.json')) {
            $dataSet = json_decode(
                file_get_contents(__DIR__ . '/../../var/configDataset.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $db = DatabaseService::getConnect();

            ## Création de la table classes
            $db->exec("CREATE TABLE IF NOT EXISTS classes (
                                id INTEGER PRIMARY KEY,
                                libelle TEXT,
                                code TEXT
                                )
                            ");

            ## Création de la table users
            $db->exec("CREATE TABLE IF NOT EXISTS users (
                                id INTEGER PRIMARY KEY,
                                idLogin INTEGER,
                                nom TEXT,
                                prenom TEXT,
                                telPortable TEXT,
                                sexe TEXT,
                                email TEXT,
                                typeCompte TEXT,
                                classeId TEXT,
                                login TEXT,
                                password TEXT,
                                uid TEXT,
                                token TEXT,
                                tokenExpiration DATETIME DEFAULT CURRENT_TIMESTAMP,
                                lastConnexion DATETIME DEFAULT CURRENT_TIMESTAMP,
                                anneeScolaireCourante TEXT
                                )
                            ");


            $faker = \Faker\Factory::create('fr_FR');

            ##<< Insertion des users dans la classe
            foreach ($dataSet["organisation"]["v3"]["classes"] as $id => $classe) {
                $db->exec("INSERT INTO classes (id, libelle, code) VALUES ($id, '" . $classe['libelle'] . "', '" . $classe['code'] . "')");

                ##<< Global etudiants dataset
                $idClasse = '{ "id": ' . $id . ', "libelle": "' . $classe['libelle'] . '", "code": "' . $classe['code'] . '" }';
                $typeCompte = "E";

                ##<< Insertion des etudiants dataset
                if (array_key_exists('etudiants', $classe)) {
                    $db->exec("INSERT INTO users (   idLogin, 
                                                               nom, 
                                                               prenom, 
                                                               telPortable, 
                                                               sexe, 
                                                               email, 
                                                               typeCompte, 
                                                               classeId, 
                                                               login, 
                                                               password, 
                                                               uid, 
                                                               tokenExpiration, 
                                                               lastConnexion, 
                                                               anneeScolaireCourante) 
                                        VALUES (    '" . $classe['etudiants'][1]['idLogin'] . "',
                                                    '" . $classe['etudiants'][1]['nom'] . "',
                                                    '" . $classe['etudiants'][1]['prenom'] . "',
                                                    '" . $classe['etudiants'][1]['telPortable'] . "',
                                                    '" . $classe['etudiants'][1]['sexe'] . "', 
                                                    '" . $classe['etudiants'][1]['email'] . "', 
                                                    '$typeCompte',
                                                    '$idClasse', 
                                                    '" . $classe['etudiants'][1]['login'] . "', 
                                                    '" . $classe['etudiants'][1]['password'] . "',  
                                                    '" . $classe['etudiants'][1]['uid'] . "',
                                                    '" . $classe['etudiants'][1]['tokenExpiration'] . "',
                                                    '" . $classe['etudiants'][1]['lastConnexion'] . "',
                                                    '" . $classe['etudiants'][1]['anneeScolaireCourante'] . "'
                                                    )
                                                ");
                } ##>> Insertion des etudiants dataset

                ##<< Insertion des etudiants faker
                $genre = ['male', 'female'];
                $posGenre = array_rand($genre);
                $nbEleves = rand(15, 35);
                for ($i = 0; $i < $nbEleves; $i++) {
                    $idLogin = $faker->numberBetween(1000, 999999);
                    $nom = $faker->lastName($genre[$posGenre]);
                    $prenom = $faker->firstName($genre[$posGenre]);
                    $telPortable = $faker->phoneNumber();
                    $sexe = strtoupper(substr($genre[$posGenre], 0, 1));
                    $email = $faker->email();

                    $login = (new \Core\StandardRaw)->normalizeSRString(substr($prenom, 0, 1)).(new \Core\StandardRaw)->normalizeSRString($nom).$faker->numberBetween(1000, 9999);
                    $password = "test";
                    $uid = $faker->uuid();
                    $tokenExpiration = date("Y-m-d H:i:s");
                    $lastConnexion = date("Y-m-d H:i:s");
                    $anneeScolaireCourante = $dataSet["organisation"]["v3"]["promo"];
                    $db->exec("INSERT INTO users (   idLogin, 
                                                               nom, 
                                                               prenom, 
                                                               telPortable, 
                                                               sexe, 
                                                               email, 
                                                               typeCompte, 
                                                               classeId, login, 
                                                               password, 
                                                               uid, 
                                                               tokenExpiration, 
                                                               lastConnexion, 
                                                               anneeScolaireCourante) 
                                        VALUES (    $idLogin, 
                                                    '$nom', 
                                                    '$prenom', 
                                                    '$telPortable', 
                                                    '$sexe', 
                                                    '$email', 
                                                    '$typeCompte', 
                                                    '$idClasse', 
                                                    '$login', 
                                                    '$password', 
                                                    '$uid', 
                                                    '$tokenExpiration', 
                                                    '$lastConnexion', 
                                                    '$anneeScolaireCourante')
                                                ");
                } ##>> Insertion des etudiants faker
            } ##>> Insertion des conf dataset
        }

        return "{message: 'initialisation the mock API Ecole Directe'}";
    }
}
