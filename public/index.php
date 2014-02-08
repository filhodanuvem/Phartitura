<?php 
error_reporting(E_ALL | E_STRICT);
require __DIR__.'/../vendor/autoload.php';

use Respect\Rest\Router; 
use Respect\Config\Container;
use Cloudson\Phartitura\Controller\PackageController; 
use Cloudson\Phartitura\Routine\Twig as TwigRoutine;
use Cloudson\Phartitura\Routine\Json as JsonRoutine;

$c = new Container(__DIR__.'/../config/parameters.ini');

$app = new Router;

$app->get('/', function () {
    return [
        '_view' => 'home.html',
    ];
});


$app->get('/*/*/*', new PackageController);


$app->always(
    'Accept',
    [
        'text/html' => new TwigRoutine($c->twig),
        'application/json' => new JsonRoutine,
    ]
);

print $app->run();