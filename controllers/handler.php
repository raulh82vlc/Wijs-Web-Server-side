<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../../src/views',
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../../logs/silex/development.log',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
	    'driver'   => 'pdo_mysql',
	    'dbhost'   => 'localhost',
	    'dbname'   => 'offices',
	    'user'     => 'root',
	    'password' => '',
	    'charset'  => 'utf8',
	 ),
));

$app->register(new Silex\Provider\FormServiceProvider());

$app['debug'] = true;

$app->get('/', function() use($app) {
    return $app['twig']->render('index.twig');
})
->bind('homepage');

$app->get('/offices', function () use($app) {
    //@TODO Autoload
    require_once(__DIR__.'/../models/Offices.php');
    $offices = new Models\Offices($app['db']);
    return $app['twig']->render('offices.twig', array(
    	'offices' => $offices->getOffices()
    ));
})
->bind('offices');

$app->get('/office/{id}', function($id) use($app) {
    //@TODO Autoload
    require_once(__DIR__.'/../models/Office.php');
    $office = new Models\Office($id, $app['db']);
    return $app['twig']->render('office.twig', array(
        'office' => $office->getOffice()
    ));
})
->bind('office');

return $app;
