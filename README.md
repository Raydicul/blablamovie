# Blablamovie api
Api Rest permetant à des utilisateurs de voter pour 3 films listés sur http://www.omdbapi.com/ 

## Installation
- Créer une base de donnée "blablamovie"
- Configurer le .env.local pour connecter la bdd
- Lancer les migrations : 
```bash
$ php bin/console doctrine:migration:migrate
```

## Utilisation
- Créer un utilisateur:
    `POST /user`
    ```json
    {
        "username": "test",
        "email": "test@gmail.com",
        "birthdayDate": "15/07/1994"
    }
    ```
- Retourne un utilisateur et ses votes:
    `GET /user/{id}`
- Retourne tous les utilisateurs aillant des votes:
    `GET /users-with-vote`
- Voter pour un film:
    `POST /vote/{user_id}`
    ```json
        {
            "imdbID": "tt0105236"
        }
    ```
- Retourne un vote:
    `GET /vote/{id}`
- Supprimer un vote:
    `DELETE /vote/{id}`
- Retourne le film avec le plus de votes dans un interval de temps donnée, ou si startDate et endDate ne sont pas précisés, retourne le film avec le plus de vote:
    `GET /vote-top?startDate="23/09/2019"&endDate="30/09/2019"`