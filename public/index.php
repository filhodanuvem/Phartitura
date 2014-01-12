<?php 

require __DIR__.'/../vendor/autoload.php';

use Respect\Rest\Router; 
use Respect\Config\Container; 

$c = new Container(__DIR__.'/parameters.ini'); 
$c->view_path; 

$app = new Router;
$app->get('/', function() {
    echo 'Say my name!';
});

$app->run();