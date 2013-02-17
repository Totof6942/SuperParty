BILAN
=====

Ce qui est fait :
-----------------

* Toutes les RQ ont été traitées.
* Les améliorations Backend ont toutes été faites.
* Utilisation de Silex pour apprendre une nouvelle techno proche de Symfony2, et pour partir sur des bases plus solides et plus complètes.
* Utilisation d'un bon nombre de bibliothèques de Symfony2 (voir README).
* Utilisation de Doctrine dbal pour l'accès à la BDD et la construction des requête.
* Utilisation du Negotition de willdurand.
* Utilisation de Geocoder de willdurand (bien qu'aucun affichage de la map par manque de temps).
* Tests de l'application avec phpunit.
* Les templates utilisent Twig mais ne portent pas l'extension .twig car nous nous sommes basé sur [Silex-Skeleton](https://github.com/fabpot/Silex-Skeleton) qui ne les utilisent pas, et nous ne l'avons pas jugé nécessaire pour une si petite application.
* Création d'un DataMapper avec une classe d'hydratation des entités.

Ce qui n'est pas fait :
-----------------------

* Des tests de l'API REST avec Goutte, en partie parce que nous avons perdu beaucoup de temps à essayer d'utiliser Goutte, pour au final ne pas y arriver.
* La traduction complète de l'application. Nous avions commencé à l'écrire en anglais avec les correspondances en français dans `resources/locales/fr.yml`, mais par manque de temps et afin de nous concentrer sur des points plus importants nous avons arrêté. L'application est donc anglais / français.
* Le SPAM Assassin.

Ce qui bug :
------------

* Le POST via l'API REST ne semble pas fonctionner. Testé avec : 
```bash
$ curl -XPOST http://localhost:8000/locations -H "Accept: application/json" -H 'Content-Type: application/json' -d '{"location":{"name":"Cubba", "adress":"Rue machin", "zip_code":"69000", "city":"Lyon", "phone":"", "description":""}}'
``` 
