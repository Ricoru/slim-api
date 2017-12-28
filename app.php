<?php
/**
 * Created by PhpStorm.
 * User: Ricoru
 * Date: 26/12/17
 * Time: 11:43
 */

date_default_timezone_set("UTC");
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require 'vendor/autoload.php';

session_start();

$config = require __DIR__ . '/src/settings.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new Slim\App($config);

require __DIR__ . '/src/middleware.php';

require __DIR__ . '/routes/base.php';
require __DIR__ . '/routes/test.php';

$app->run();