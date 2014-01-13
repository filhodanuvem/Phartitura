<?php 

error_reporting(E_ALL | E_STRICT);
require __DIR__.'/../vendor/autoload.php';

use Respect\Rest\Router; 
use Respect\Config\Container;
use Cloudson\Phartitura\Controller\PackageController; 

$c = new Container(__DIR__.'/parameters.ini');

$app = new Router;
$app->get('/*/*', new PackageController);

$app->run();