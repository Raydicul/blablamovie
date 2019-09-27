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
- `POST /user` : Créer un utilisateur
    ```json
    {
        "username": "test",
        "email": "test@gmail.com",
        "birthdayDate": "15/07/1994"
    }
    ```
- `GET /user/{id}` : Retourne un utilisateur et ses votes
    
- `GET /users-with-vote` : Retourne tous les utilisateurs aillant des votes
    
- `POST /vote/{user_id}` : Voter pour un film
    ```json
        {
            "imdbID": "tt0105236"
        }
    ```
    
- `GET /vote/{id}` : Retourne un vote
    
- `DELETE /vote/{id}` : Supprimer un vote
    
- `GET /vote-top?startDate="23/09/2019"&endDate="30/09/2019"` : Retourne le film avec le plus de votes dans un interval de temps donné, ou si `startDate` et `endDate` ne sont pas précisés, retourne le film avec le plus de vote
