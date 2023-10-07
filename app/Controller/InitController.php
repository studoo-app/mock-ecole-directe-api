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
use Core\DatabaseService;

class InitController implements ControllerInterface
{
    public function execute(Request $request): string|null
    {
        $db = DatabaseService::getConnect();

        ## Création de la table classes
        $db->exec("CREATE TABLE IF NOT EXISTS classes (
                                id INTEGER PRIMARY KEY,
                                libelle TEXT,
                                code TEXT
                                )
                            ");

        ##<< Insertion des classes
        $classes = [
            "107" => [
                "libelle" => "1 TS SIO A",
                "code" => "1TSSIOA"
            ],
            "140" => [
                "libelle" => "Manager Solutions Digitals et Data 2ème année alternance",
                "code" => "MS2D2ALT"
            ],
            "150" => [
                "libelle" => "1 TS SIO B",
                "code" => "1TSSIOB"
            ],
            "102" => [
                "libelle" => "2TS SIO SLAM",
                "code" => "2TSSIOSLAM"
            ]
        ];
        ##>> Insertion des classes

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
                                classeId INTEGER,
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
        foreach ($classes as $id => $classe) {
            $db->exec("INSERT INTO classes (id, libelle, code) VALUES ($id, '" . $classe['libelle'] . "', '" . $classe['code'] . "')");

            $genre = ['male', 'female'];
            $pos = array_rand($genre);

            $nbEleves = rand(20, 30);
            for ($i = 0; $i < $nbEleves; $i++) {
                $nom = $faker->lastName($genre[$pos]);
                $prenom = $faker->firstName($genre[$pos]);
                $telPortable = $faker->phoneNumber();
                $sexe = strtoupper(substr($genre[$pos], 0, 1));
                $email = $faker->email();
                $typeCompte = "E";
                $login = $nom . '.' . $prenom;
                $password = "test";
                $uid = "uid" . $i;
                $tokenExpiration = date("Y-m-d H:i:s");
                $lastConnexion = date("Y-m-d H:i:s");
                $anneeScolaireCourante = "2020-2021";
                $db->exec("INSERT INTO users (idLogin, nom, prenom, telPortable, sexe, email, typeCompte, classeId, login, password, uid, tokenExpiration, lastConnexion, anneeScolaireCourante) VALUES ($id, '$nom', '$prenom', '$telPortable', '$sexe', '$email', '$typeCompte', $id, '$login', '$password', '$uid', '$tokenExpiration', '$lastConnexion', '$anneeScolaireCourante')");
            }
        }

        return "{message: 'initialisation the mock API Ecole Directe'}";
    }
}
