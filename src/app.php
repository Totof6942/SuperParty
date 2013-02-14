<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Translation\Loader\YamlFileLoader as YamlTranslation;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader as YamlValidator;

$app = new Application();

$_SERVER['SERVER_PORT'] = 8000;

$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new DoctrineServiceProvider());

$app->register(new SecurityServiceProvider(), array(
    $app['security.firewalls'] = array(
        'admin' => array(
            'pattern' => '^/admin/',
            'form'    => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'logout'  => array('logout_path' => '/admin/logout'),
            'users'   => array(
                'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
            ),
        ),
    )
));

$app->register(new TwigServiceProvider(), array(
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html'),
    'twig.path'           => array(__DIR__.'/../templates'),
    // 'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));

$app->register(new TranslationServiceProvider());
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlTranslation());

    $translator->addResource('yaml', __DIR__.'/../resources/locales/fr.yml', 'fr');

    return $translator;
}));

function controller($shortName)
{
    list($shortClass, $shortMethod) = explode('/', $shortName, 2);

    return sprintf('Controller\%sController::%sAction', ucfirst($shortClass), $shortMethod);
}

// Configure the validator service
$app['validator.mapping.class_metadata_factory'] = new ClassMetadataFactory(
    new YamlValidator(__DIR__ . '/../config/validation.yml')
);

return $app;
