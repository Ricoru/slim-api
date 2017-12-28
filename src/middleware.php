<?php
/**
 * Created by PhpStorm.
 * User: Ricoru
 * Date: 26/12/17
 * Time: 13:49
 */

use App\Token;
use Gofabian\Negotiation\NegotiationMiddleware;
use Tuupola\Middleware\CorsMiddleware;
use \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator;
Use \Slim\Middleware\JwtAuthentication\RequestPathRule;

$container = $app->getContainer();

//container de token
$container["token"] = function ($container) {
    return new Token;
};

//container de log
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('slim-app');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
    /*$logger = new Logger("slim");
    $formatter = new LineFormatter(
        "[%datetime%] [%level_name%]: %message% %context%\n",
        null,
        true,
        true
    );
    $rotating = new RotatingFileHandler(__DIR__ . "/../logs/app.log", 0, Logger::DEBUG);
    $rotating->setFormatter($formatter);
    $logger->pushHandler($rotating);*/
    return $logger;
};

//container de db
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

//"path" => "/", /* or ["/admin", "/api"] */
$container["HttpBasicAuthentication"] = function ($container) {
    return new \Slim\Middleware\HttpBasicAuthentication([
        "path" => "/token",
        "secure" => false,
        "passthrough" => ["/token","/info"],
        "realm" => "Protected",
        "relaxed" => ["127.0.0.1", "localhost"],
        "environment" => "REDIRECT_HTTP_AUTHORIZATION",
        /*"authenticator" => new PdoAuthenticator([
            "pdo" => $container["db"],
            "table" => "users",
            "user" => "username",
            "hash" => "hashed"
        ]),*/
        "users" => [
            "admin" => getenv("USER_PASSWORD")
        ],
        "error" => function ($request, $response, $arguments) {
            //return new UnauthorizedResponse($arguments["message"], 401);
            $data = [];
            $data["status"] = "error basic";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES));
        },
        "callback" => function ($request, $response, $arguments) {
            print_r($arguments);
        },
    ]);
};

$container["JwtAuthentication"] = function ($container) {
    return new \Slim\Middleware\JwtAuthentication([
        /*"path" => "/",
        "passthrough" => ["/token", "/info"],*/
        "rules" => [
            new RequestPathRule([
                "path" => "/",
                "passthrough" => ["/token", "/info"]
            ])
        ],
        "logger" => $container["logger"],
        "attribute" => false,
        "algorithm" => ["HS256"],
        "relaxed" => ["127.0.0.1", "localhost"],
        "secret" => getenv("JWT_SECRET"),
        "error" => function ($request, $response, $arguments) {
            //return new UnauthorizedResponse($arguments["message"], 401);
            $data["status"] = "error jwt";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        },
        "before" => function ($request, $response, $arguments) use ($container) {
            $container["token"]->hydrate($arguments["decoded"]);
        },
        "callback" => function ($request, $response, $arguments) use ($container) {
            $container["token"]->hydrate($arguments["decoded"]);
        }
    ]);
};

$container["CorsMiddleware"] = function ($container) {
    return new CorsMiddleware([
        "origin" => ["*"],
        "logger" => $container["logger"],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
        "headers.expose" => ["Etag"],
        "credentials" => true,
        "cache" => 60,//86400,
        "error" => function ($request, $response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]);
};

$container["Negotiation"] = function ($container) {
    return new NegotiationMiddleware([
        "accept" => ["application/json"]
    ]);
};

//Filtro example middleware
$mw = function ($request, $response, $next) {
    //$response->getBody()->write('BEFORE');
    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');
    return $response;
};

$app->add("HttpBasicAuthentication");
$app->add("JwtAuthentication");
$app->add("CorsMiddleware");
$app->add("Negotiation");