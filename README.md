![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# Mock Ecole Directe API PHP
![Docker Pulls](https://img.shields.io/docker/pulls/bfoujols/mock-api-ecole-directe)

Création du mock de l'API Ecole Directe pour les tests unitaires. \
Celui-ci est basé sur le projet [studoo/ecole-directe-api](https://github.com/studoo-app/ecole-directe-api)

## Installation d'image Docker
Ce rendre sur la page des [docker/bfoujols/mock-api-ecole-directe](https://hub.docker.com/r/bfoujols/mock-api-ecole-directe) et récupérer la dernière version. 

```bash
docker pull bfoujols/mock-api-ecole-directe
```
## Démarage de l'image Docker
```bash
docker run --name api-ecole-directe -p 9042:80 -d bfoujols/mock-api-ecole-directe
```

Vous pouvez maintenant accéder à l'API via l'adresse http://localhost:9042 \
Vous pouvez tester l'API via Insomnia :
[![Run in Insomnia}](https://insomnia.rest/images/run.svg)](https://insomnia.rest/run/?label=MOCK%20API%20ECOLE%20DIRECTE&uri=https%3A%2F%2Fraw.githubusercontent.com%2Fstudoo-app%2Fmock-ecole-directe-api%2Fmain%2FInsomnia.json)

## Utilisation de l'API Ecole Directe Mock

Ce mock auto-génère des données à travers des fakers :

### API : /dataset/classes

Visualisation des données des classes

### API : /dataset/etudiants

Visualisation des données des étudiants

### API : /dataset/profs

Visualisation des données des profs

### API : /login

Certains users sont configurés dans le fichier de configDataSet

| Login/Password     	     | Role              	 | Niveau              	        | Id Niveau     |
|--------------------------|----------------|------------------------------|---------------|
| FPELTIER/test          	    | Etudiante    	 | BTS SIO 1 année A (1TSSIOA)  | 107           |
| PTORRE/test          	     | Etudiante    	 | BTS SIO 1 année A (1TSSIOA)  | 107           |
| JBOULLAM/test          	     | Etudiant    	 | Bachelor CSI | 140           |

### API : /classes

| Login/Password     	     | Role              	 | Id Classe           |
|--------------------------|----------------|---------------------|
| JBOUVIER/test          	 | Prof     	     | 107,120,134,140,141 |
| BDAN/test          	 | Prof     	     | 107,120             |

## Personnalisation du mock

Voici les étapes à suivre :

### Faire un fichier "docker-compose.yml"
La variable "container_name" doit être changé afin d'éviter tout conflit.
La variable "ports" est le port (9042) accessible depuis l'extérieur de Docker. Celui-ci peut être personnalisé, néanmoins le port interne est 80 (apache).
Le volume sert à rendre accessible dans votre projet les fichiers de configuration et la base de données SQLite.

```yaml
version: "3"  
services:
  api:  
    container_name: mock-api-ecole-directe  
    image: bfoujols/mock-api-ecole-directe:latest  
    ports:  
      - "9042:80"
    volumes:  
      - ./var/dbdataset:/var/www/mock-ecole-directe-api/var  
volumes:  
  dbdata:
```

### Télécharger le fichier de configuration du DataSet

Pour permettre la personnalisation, vous devez télécharger le modèle de configuration via l'URL suivante.
```
curl -fsS https://raw.githubusercontent.com/studoo-app/mock-ecole-directe-api/main/var/configDataset.json > var/dbdataset/configDataset.json
```

Ce modèle exemple vous servira à comprendre les différents paramètres pour :

#### Personnaliser l'établissement
Vous pouvez personnaliser votre établissement :
```json
{  
  "organisation" : {  
    "nomEtablissement" : "<String>", // Nom de l'etablissement
    "codeOgec" : "<String>", // Code de l'établissement
    [...]
    }
}
```


Exemple :
```json
{  
  "organisation" : {  
    "nomEtablissement" : "STUDOO SCHOOL",  
    "codeOgec" : "0345587-NC",
    [...]
    }
}
```

#### Ajouter des classes et année de promotion
Dans la section "organisation" puis  "V3" (version de APi), vous pouvez personnaliser les classes :
```json
      "promo" : "<String>", // Promotion avec le format YYYY-YYYY
```

Exemple :
```json
      "promo" : "2023-2024",
```

Dans la section "organisation" puis  "V3" (version de APi), vous pouvez personnaliser les classes :
```json
"classes" : {  
	"<Interger>" : { // ID unique de la classe
	  "libelle" : "<String>", // Libellé de la classe
	  "code" : "<String>" // Code de la classe
}, 
```

Exemple :
```json
{  
  "organisation" : {  
	[...] 
    "v3" : {  
      "promo" : "2023-2024",  
      "classes" : {  
        "107" : {  
          "libelle" : "1 TS SIO A",  
          "code" : "1TSSIOA" 
        },  
        "120" : {  
          "libelle" : "1 TS SIO B",  
          "code" : "1TSSIOB"  
        },  
        "134" : {  
          "libelle" : "1 TS SIO - année alternance",  
          "code" : "1TSSIOALT"  
        },
```

#### Ajouter des étudiants fixes
L'ensemble des étudiants sont généré par un faker. Pour des soucis de test, vous pouvez figer des étudiants dans le fichier de configuration. Par classe, chaque étudiant peut être présent :
```json
"classes" : {  
	"<Interger>" : { // ID unique de la classe
	  "libelle" : "<String>", // Libellé de la classe
	  "code" : "<String>" // Code de la classe
	 ."etudiants" : {  
		  "<Integer>" : { // Position dans le tableau insertion dans la base
			"nom": "<String>", // Nom de l'étudiant
			"prenom": "<String>", // Prenom de l'étudiant
			"email": "<String>", // Email de l'étudiant
			"telPortable": "<String>", // Téléphone de l'étudiant
			"sexe": "<String (F ou M)>", // Genre de l'étudiant
			"login": "<String>", // Login de l'étudiant
			"idLogin" : "<Integer>", // Id interne afin de récuperer l'étudiant en cas de changement majeur
			"password": "<String>", // Mot de passe de l'étudiant (Non crypté pour des soucis de test)
			"uid": "<String>",  // UID de l'étudiant 16bit https://fr.wikipedia.org/wiki/Universally_unique_identifier
			"tokenExpiration": "<DateTime YYYY-MM-DD HH:MM:SS>", // La date limite d'expiration du token d'authentification
			"lastConnexion": "<DateTime YYYY-MM-DD HH:MM:SS>", // Dernier connexion à l'application
			"anneeScolaireCourante": "<String YYYY-YYYY>" // Année de promotion de l'étudiant
	      },
```

Exemple :
```json
"classes" : {  
  "107" : {  
    "libelle" : "1 TS SIO A",  
    "code" : "1TSSIOA",  
    "etudiants" : {  
      "1" : {  
        "nom": "Peltier",  
        "prenom": "Flore",  
        "email": "fp@test.com",  
        "telPortable": "07 23 45 23 89",  
        "sexe": "F",  
        "login": "FPELTIER",  
        "idLogin" : "7647334",  
        "password": "test",  
        "uid": "98432dd9-c483-3669-a31e-153fec2eac72",  
        "tokenExpiration": "2023-10-08 10:10:03",  
        "lastConnexion": "2023-10-08 10:10:03",  
        "anneeScolaireCourante": "2020-2021"  
      },
```

#### Ajouter des profs
Dans la section "organisation" puis  "V3" (version de APi), vous pouvez personnaliser les profs : (Les variables sont équivalentes à la structure d'étudiant)
```json
"professeurs" : {  
  "<integer>": {  
    "nom": "<String>",  
    "prenom": "<String>",  
    "email": "<String>",  
    "telPortable": "<String>",  
    "sexe": "<String>",  
    "login": "<String>",  
    "idLogin" : "<Integer>",  
    "password": "<String>",  
    "uid": "<String>",  
    "tokenExpiration": "<DateTime YYYY-MM-DD HH:MM:SS>",  
    "lastConnexion": "<DateTime YYYY-MM-DD HH:MM:SS>",  
    "anneeScolaireCourante": "<String>",  
    "classes" : "<String>"  
  },
```


Exemple :
```json
"professeurs" : {  
  "1": {  
    "nom": "Bouvier",  
    "prenom": "Julien",  
    "email": "Julien.Bouvier@test.fr",  
    "telPortable": "07 11 81 23 83",  
    "sexe": "M",  
    "login": "JBOUVIER",  
    "idLogin" : "34867434",  
    "password": "test",  
    "uid": "0f9fdc75-fd68-3fe0-8a09-1864f80025f7",  
    "tokenExpiration": "2023-10-08 10:10:03",  
    "lastConnexion": "2023-10-08 10:10:03",  
    "anneeScolaireCourante": "2020-2021",  
    "classes" : "107,120,134,140,141"  
  },
```


### Démarrer les containers Docker

```
docker compose up -d
```

### Générer le DataSet

```
http://localhost:9042/init
```