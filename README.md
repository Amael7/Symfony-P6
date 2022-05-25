# symfonyP6

## Informations du projet
Projet de la formation Développeur d'application - PHP / Symfony.

Développez de A à Z le site communautaire SnowTricks

### Version du projet

- PHP 8.0.0
- mysql 5.7
- Symfony CLI version 5.3.0
- Composer version 2.2.5
- Node v16.14.2

### Installation

1.Clonez le repo :
      
        git clone https://github.com/Amael7/symfonyP6.git

2.Modifier le .env avec vos informations.

3.Installez les dependances :

         composer install
         npm install

4.Build les assets:

         npm run build

5.Mettez en place la BDD :

         php bin/console doctrine:database:create
         php bin/console doctrine:migrations:migrate

6.Implementez les fixtures :

         php bin/console doctrine:fixtures:load
         
7.Lancer les serveur :
  
         symfony serve
         yarn dev-server
         mailhog (Pour la réception de mail en local)
