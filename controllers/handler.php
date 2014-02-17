<?php
/**
*  Main controller, which handles the whole flow and enables libraries, requests and mySQL interaction
*
* @Author Raul Hernandez Lopez - 2014
**/
require_once __DIR__.'/../../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;


/**
*  Initialises the Silex application
**/
$app = new Silex\Application();

/**
*  Enables requests
**/
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

/**
*  Enables twig views
**/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../../src/views',
));

/**
*  Enables debugger service provider´s log
**/
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../../logs/silex/development.log',
));

/**
*  URL generator
**/
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/**
*  MySQL settings
**/
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

/**
*  Debugger mode on
**/
$app['debug'] = true;

/**
*  Home initialisation
**/
$app->get('/', function() use($app) {
    return $app['twig']->render('index.html.twig');
})
->bind('homepage');

/**
*  List of offices initialisation
**/
$app->get('/offices', function () use($app) {

    require_once(__DIR__.'/../models/Offices.php');
    $offices = new Models\Offices($app['db']);
    return $app['twig']->render('offices.html.twig', array(
    	'offices' => $offices->getOffices()
    ));
})
->bind('offices');

/**
*  Invidivual office info initialisation, when clicking on the link within the List of Offices
**/
$app->get('/office/{city}', function($city) use($app) {

    require_once(__DIR__.'/../models/Office.php');
    $office = new Models\Office($city, $app['db']);
    return $app['twig']->render('office.html.twig', array(
        'office' => $office->getOffice()
    ));
})
->bind('office');

/**
*  Search controller, this is the responsible of the final output fro, the search
**/
$app->get('/search', function (Request $request) use ($app) {

    require_once(__DIR__.'/../models/OfficesFiltered.php');
    
    // Communication with the client after request, waiting response from the server once OfficesFiltered model extracts 
    // the closeby offices
    $response = array(
        'city'                => $request->query->get('city'),
        'is_open_in_weekends' => $request->query->get('is_open_in_weekends'),
        'has_support_desk'    => $request->query->get('has_support_desk')        
    );

    $officesFiltered = new Models\OfficesFiltered($response['city'], $response['is_open_in_weekends'], $response['has_support_desk'], $app['db']);
    return $app['twig']->render('search.html.twig', array(
        'offices' => $officesFiltered->getOfficesFiltered()
    ));
});

/**
*  Search initialisation, this does not handle the final output, just initialises
**/
$app->get('/search', function() use($app) {
    require_once(__DIR__.'/../models/Offices.php');
    $offices = new Models\Offices($app['db']);
    return $app['twig']->render('search.html.twig', array(
        'offices' => $offices->getOffices()
    ));
})
->bind('search');

return $app;
