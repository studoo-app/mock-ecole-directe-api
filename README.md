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

Ce mock auto-génère des données aléatoires pour les tokens authentifications :

| Login/Password     	     | Role              	 | Niveau              	 
|--------------------------|----------------|-----------------------|
| flore/test          	    | Etudiante    	 | BTS SIO 1 année A     |
| julie/test          	    | Etudiante    	 | BTS SIO 1 année B     |
| nico/test          	     | Etudiant    	 | BTS SIO 2 année       |
| jeremy/test          	   | Etudiant    	 | MASTER 2              |
| jbouvier/test          	 | Prof     	     | BTS SIO ADM           |
| bdan/test          	     | Prof     	     | BTS SIO ADM           |

## Pour developper le mock
Télécharger le projet depuis GitHub et se rendre dans le dossier
```bash
composer install
```
Démarrer un serveur PHP
```bash
php -S localhost:9042 -t api
```

## Pour developper l'image Docker

Télécharger le projet depuis GitHub et se rendre dans le dossier
```bash
composer install
```

### Build
```bash
docker-compose build --no-cache
```

### Start
```bash
docker-compose up -d
```


### Deploy sur Docker Hub

Vous devez la taguer avec votre nom d'utilisateur Docker Hub ou le nom de votre registre privé, ainsi qu'un nom de référentiel et un tag
```bash
docker tag mock-ecole-directe-api-app bfoujols/mock-api-ecole-directe:<VERSION>
```

Maintenant, vous pouvez pousser l'image vers le registre en utilisant la commande "docker push"
```bash
docker push bfoujols/mock-api-ecole-directe:<VERSION>
```
