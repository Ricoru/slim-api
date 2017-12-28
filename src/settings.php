<?php
/**
 * Created by PhpStorm.
 * User: Ricoru
 * Date: 26/12/17
 * Time: 12:56
 */


// Create and configure Slim app
//Config Web
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['determineRouteBeforeAppMiddleware'] = true;
$config['debug'] = true;

$config['logger'] = [
    'name' => 'slim-app',
    'level' => Monolog\Logger::DEBUG,
    'path' => __DIR__ . '/logs/app.log',
];

//Database
$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = 'toor';
$config['db']['dbname'] = 'cmclmcom_condominio';

return [
    'settings' => $config,
];