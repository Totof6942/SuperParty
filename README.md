SuperParty
==========

[![Build Status](https://travis-ci.org/Totof6942/SuperParty.svg?branch=master)](https://travis-ci.org/Totof6942/SuperParty) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Totof6942/SuperParty/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Totof6942/SuperParty/?branch=master)

**SuperParty** developed by **[Claude Dioudonnat](https://github.com/claudusd)** and **[Christophe Poulette](https://github.com/Totof6942)** for the PHP practicals supervised by [Julien Muetton](https://github.com/themouette) and [William Durand](https://github.com/willdurand).

HTML / CSS / Javascripts
------------------------

* [Twitter Bootstrap](http://twitter.github.com/bootstrap/) version 2.3.0
* [Google-styled](https://github.com/todc/todc-bootstrap)

Extensions included
-------------------

* [Doctrine dbal](https://github.com/doctrine/dbal)
* [Negotiation](https://github.com/willdurand/Negotiation)
* [Silex](https://github.com/fabpot/Silex)
* [Symfony Form](https://github.com/symfony/Form)
* [Symfony Locale](https://github.com/symfony/Locale)
* [Symfony Security](https://github.com/symfony/Security)
* [Symfony Serializer](https://github.com/symfony/Serializer)
* [Symfony Validator](https://github.com/symfony/Validator)
* [Symfony Yaml](https://github.com/symfony/Yaml)
* [Twig](https://github.com/fabpot/Twig)

Installation
------------

Run these two commands to install it:

``` bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install --dev --prefer-source
```

Configure database access in `config/prod.php`.

Install the database:

``` bash
$ mysql -u<user> -p<password> <database> < config/schema.sql
```

If you want, there is a set of datas in `datas/project.sql`.

And if you use the application in localhost with a port, configure the port in `src/app.php` on line 19.

Administration login : **admin/foo**

License
-------

SuperParty is released under the MIT License. See the bundled LICENSE file for details.

