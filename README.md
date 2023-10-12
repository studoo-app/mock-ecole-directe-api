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

```
curl -fsS https://raw.githubusercontent.com/studoo-app/mock-ecole-directe-api/main/var/configDataset.json > var/configDataset.json
```

### Démarrer les containers Docker

```
docker compose up -d
```

### Générer le DataSet

```
http://localhost:9042/init
```

Le fichier de configuration "configDataset.json" peut être personnalisé :
- Ajouter des étudiants fixes
- Ajouter des classes
- Ajouter des profs

