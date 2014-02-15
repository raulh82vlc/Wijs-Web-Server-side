<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;


$app = new Silex\Application();

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
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
    return $app['twig']->render('index.html.twig');
})
->bind('homepage');

$app->get('/offices', function () use($app) {
    //@TODO Autoload
    require_once(__DIR__.'/../models/Offices.php');
    $offices = new Models\Offices($app['db']);
    return $app['twig']->render('offices.html.twig', array(
    	'offices' => $offices->getOffices()
    ));
})
->bind('offices');

$app->get('/office/{city}', function($city) use($app) {
    //@TODO Autoload
    require_once(__DIR__.'/../models/Office.php');
    $office = new Models\Office($city, $app['db']);
    return $app['twig']->render('office.html.twig', array(
        'office' => $office->getOffice()
    ));
})
->bind('office');

$app->get('/office', function (Request $request) use ($app) {

    require_once(__DIR__.'/../models/Office.php');
    require_once(__DIR__.'/../models/OfficeFiltered.php');
    
    $response = array(
        'city'                => $request->query->get('city'),
        'is_open_in_weekends' => $request->query->get('is_open_in_weekends'),
        'has_support_desk'    => $request->query->get('has_support_desk')        
    );

    $office = new Models\OfficeFiltered($response['city'], $response['is_open_in_weekends'], $response['has_support_desk'], $app['db']);
    return $app['twig']->render('index.html.twig', array(
        'office' => $office->getOffice()
    ));

});

return $app;
