<?php

use Negotiation\FormatNegotiator;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader as YamlTranslation;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader as YamlValidator;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new DoctrineServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html'),
    'twig.path'           => array(__DIR__.'/../templates'),
));

$app->register(new TranslationServiceProvider());
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlTranslation());

    $translator->addResource('yaml', __DIR__.'/../resources/locales/fr.yml', 'fr');

    return $translator;
}));

$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern'   => '^.*$',
            'anonymous' => true,
            'form'      => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'logout'    => array('logout_path' => '/admin/logout'),
            'users'     => array(
                'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
            ),
        ),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));

// Configure the validator service
$app['validator.mapping.class_metadata_factory'] = new ClassMetadataFactory(
    new YamlValidator(__DIR__ . '/../config/validation.yml')
);

// Controller loader
function controller($shortName)
{
    list($shortClass, $shortMethod) = explode('/', $shortName, 2);

    return sprintf('Controller\%sController::%sAction', ucfirst($shortClass), $shortMethod);
}

// Content negotiation
function guessBestFormat()
{
    $negociator = new FormatNegotiator();

    $acceptHeader = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : 'text/html';
    $priorities = array('html', 'json', '*/*');

    return $negociator->getBest($acceptHeader, $priorities);
}

return $app;
