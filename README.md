# ToDo & Co
---
[![SymfonyInsight](https://insight.symfony.com/projects/ef99b0a5-b464-480e-bd32-1d6981b962f4/big.svg)](https://insight.symfony.com/projects/ef99b0a5-b464-480e-bd32-1d6981b962f4)

### Requirements
1. PHP 8.2.0
2. Composer
3. WAMPServer
4. xdebug

### Installation
1. Create a Virtual Host
2. Configurate the ".env" file so that it corresponds to your environnement
3. Install dependencies : ```composer install```
4. Create the database : ```php bin/console doctrine:database:create```
5. Create a migration : ```php bin/console make:migration```
6. Update the database structure : ```php bin/console doctrine:migrations:migrate```
7. Upload the DataFixtures : ```php bin/console doctrine:fixtures:load```

### Usage/Access
1. You can find 2 users (1 Admin & 1 Standard) in the ```credentials.txt``` file provided
2. Access the project : ```{your-virtual-host}/login```
