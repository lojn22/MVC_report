MVC - Model View Controll  
---  
Detta repositroy tillhör kursen MVC på programmet Webbprogrammering vid Blekinge Tekniksa Högskola i Karlskrona.
Repot innehåller praktiska övingsuppgifter som vi har utfört under kursens gång.  
Kursen fokuserar på:  
Objektorienterad PHP-programmering med ramverket Symfony.  
Byggande av RESTful API med JSON.  
Enhetstestning med PHPUnit.   
Databashantering och Objekt Realtional Mappning(ORM) med Doctrine.  
Kodkvalitet och CI/CD med PHPmetrics och Scrutinizer.


Scrutinizer badges from my project.

[![Build Status](https://scrutinizer-ci.com/g/lojn22/MVC_report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/lojn22/MVC_report/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/lojn22/MVC_report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/lojn22/MVC_report/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lojn22/MVC_report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/lojn22/MVC_report/?branch=main)

---  

Klona repot:
1. Klona repositoryt
```
git clone https://github.com/lojn22/MVC_report.git
```
2. Installera beroenden
```
composer install
```
3. Skapa databasen med Doctrine
```
#Skapa databasen
php bin/console doctrine:database:create

#Ny migrationsfil i migrations/-mappen 
php bin/console make:migration

#Kör migrationen och skapa tabellerna i databasen
php bin/console doctrine:migrations:migrate

#Om repository innehåller fixtures
php bin/console doctrine:fixtures:load
```
4. Starta servern
```
php -S localhost:8888 -t public
```
Gå till http://localhost:8888/public/
