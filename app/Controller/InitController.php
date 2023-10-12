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
        if (ConfigDataSet::get(__DIR__ . '/../../var/configDataset.json') !== false) {
            $dataSet = ConfigDataSet::get(__DIR__ . '/../../var/configDataset.json');

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
                    foreach ($classe['etudiants'] as $etudiant)
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
                                        VALUES (    '" . $etudiant['idLogin'] . "',
                                                    '" . $etudiant['nom'] . "',
                                                    '" . $etudiant['prenom'] . "',
                                                    '" . $etudiant['telPortable'] . "',
                                                    '" . $etudiant['sexe'] . "',
                                                    '" . $etudiant['email'] . "',
                                                    '$typeCompte',
                                                    '$idClasse',
                                                    '" . $etudiant['login'] . "',
                                                    '" . $etudiant['password'] . "',
                                                    '" . $etudiant['uid'] . "',
                                                    '" . $etudiant['tokenExpiration'] . "',
                                                    '" . $etudiant['lastConnexion'] . "',
                                                    '" . $etudiant['anneeScolaireCourante'] . "'
                                                    )
                                                ");
                } ##>> Insertion des etudiants dataset

                ##<< Insertion des etudiants faker
                $genre = ['male', 'female'];
                $posGenre = array_rand($genre);
                $nbEleves = rand(15, 35);
                for ($i = 0; $i < $nbEleves; $i++) {
                    $nom = $faker->lastName($genre[$posGenre]);
                    $prenom = $faker->firstName($genre[$posGenre]);
                    $sexe = strtoupper(substr($genre[$posGenre], 0, 1));
                    $login = (new \Core\StandardRaw)->normalizeSRString(substr($prenom, 0, 1)).(new \Core\StandardRaw)->normalizeSRString($nom).$faker->numberBetween(1000, 9999);
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
                                                               anneeScolaireCourante
                                                               ) 
                                            VALUES (    " . $faker->numberBetween(1000, 999999) . ",
                                                        '$nom',
                                                        '$prenom',
                                                        '" . $faker->phoneNumber() . "',
                                                        '$sexe',
                                                        '" . $faker->email() . "',
                                                        '$typeCompte',
                                                        '$idClasse',
                                                        '$login',
                                                        'test',
                                                        '" . $faker->uuid() . "',
                                                        '" . date("Y-m-d H:i:s") . "',
                                                        '" . date("Y-m-d H:i:s") . "',
                                                        '" . $dataSet["organisation"]["v3"]["promo"] . "'
                                                    )
                                        ");
                } ##>> Insertion des etudiants faker
            } ##>> Insertion des users dans la classe

            ##<< Insertion des professeurs dataset
            foreach ($dataSet["organisation"]["v3"]["professeurs"] as $professeur) {

                $idClassesTemp = [];
                foreach (explode(",", $professeur['classes']) as $classe) {
                    if (array_key_exists($classe, $dataSet["organisation"]["v3"]["classes"])) {
                        $idClassesTemp[] = [
                            "id" => $classe,
                            "code" => $dataSet["organisation"]["v3"]["classes"][$classe]["code"],
                            "libelle" => $dataSet["organisation"]["v3"]["classes"][$classe]["libelle"],
                            "idGroupe" => 0
                        ];
                    }
                }

                $idClasse = json_encode($idClassesTemp, JSON_THROW_ON_ERROR);


                $db->exec("INSERT INTO users (       idLogin, 
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
                                                               anneeScolaireCourante
                                                               ) 
                                        VALUES (    '" . $professeur['idLogin'] . "',
                                                    '" . $professeur['nom'] . "',
                                                    '" . $professeur['prenom'] . "',
                                                    '" . $professeur['telPortable'] . "',
                                                    '" . $professeur['sexe'] . "',
                                                    '" . $professeur['email'] . "',
                                                    'P',
                                                    '" . $idClasse . "',
                                                    '" . $professeur['login'] . "',
                                                    '" . $professeur['password'] . "',
                                                    '" . $professeur['uid'] . "',
                                                    '" . $professeur['tokenExpiration'] . "',
                                                    '" . $professeur['lastConnexion'] . "',
                                                    '" . $professeur['anneeScolaireCourante'] . "'
                                                    )
                                                ");
            } ##>> Insertion des professeurs dataset

            return "{message: 'initialisation du mock API Ecole Directe'}";
        }

        return "{message: 'Probleme d'initialisation du mock API Ecole Directe'}";
    }
}
