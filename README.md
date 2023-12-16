hit-joomla
==========

Repo voor Joomla extensions voor de HIT van Scouting Nederland.

Bouwen Docker Image
-------------------
In de map `docker/image` staat een script `create-image.sh`. Met dit script wordt een nieuwe Docker-image gemaakt die je kan starten voor het ontwikkelen aan de HIT Joomla componenten.

Als het script eindigt met de mededeling "Successfully tagged hit_dev_docker:latest", dan is het gelukt en kun je het image gebruiken. Het is echter wel handig om eerst alle componenten te bouwen, want dan krijg je die gelijk voorgeïnstalleerd.


Starten Docker
--------------
Gebruik de eerste keer het `docker/j4setup.sh`-script. Dit start alles op en installeert componenten, templates, maakt user-groups aan en ook users.

De website kun je dan benaderen via http://localhost en de backend via http://localhost/administrator.


Bouwen applicatie
-----------------
In de root van het project staat een Ant build-file, `build.xml`.

Met het commando `ant com_kampinfo` wordt het component gebouwd en in de map `docker/j4/work_directory/kampinfo` geplaatst.

Voor de overige componenten geldt hetzelfde:

- com_kampinfoimexport
- plg_kampinfo
- plg_group_cli
- plg_kampinfo_cli


Deployen applicatie op lokale Docker
------------------------------------
Start een shell met het script in docker/j4/shell.sh.

Met het CLI tool `cli/joomla.php` kun je vanaf de command line allerlei commando's uitvoeren voor het beheer van Joomla.

Het deployen van een eerder gebouwde applicatie gaat als volgt:

Standaard kom je met een shell in de map `/var/www/html`, maar de work_directory staat een niveau hoger. Als je in het pad naar de zip een `..` hebt staan, krijg je een foutmelding, maar als je een niveau hoger gaat staan, gaat het goed.
Dus:
`cd ..`

`php joomla/cli/joomla.php extension:install --path work_directory/kampinfo/com_kampinfo-2.0.0-*`

Als het niet werkt:
- Als je een of andere foutmelding krijgt met "Copy Failed", dan is de owner van de component mappen niet goed in `/var/www/html/administrator/components`. Die kun je dan goed zetten met `chown -R www-data:www-data /var/www/html/administrator/componentscom_kampinfo*`.
- Let op dat je in `/var/www` staat en NIET in `/var/www/html`.

Inloggen op de backend
----------------------
Bij de eerste keer opstarten wordt er ook een user `admin` gemaakt. Deze heeft als wachtwoord: `adminadminadmin`. Dat is ook een manier om aan de minimale lengte te komen...

