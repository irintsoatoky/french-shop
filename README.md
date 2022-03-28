## Installation
```
git clone https://github.com/irintsoatoky/french-shop.git
cd french-shop
composer install
```

## Configuration
Créer un fichier `.env.local` : 
```dotenv
DATABASE_URL=mysql://DATABASE_USER:@DATABASE_HOST:3306/DATABASE_NAME
```

## Configuration de la base de donnée
```
php bin/console d:d:c
php bin/console d:s:u -f
```

## Démarrer le serveur
```
symfony serve
```

💻 Happy coding 🥳