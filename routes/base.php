<?php
/**
 * Created by PhpStorm.
 * User: Ricoru
 * Date: 26/12/17
 * Time: 16:29
 */

use \Firebase\JWT\JWT;
use Tuupola\Base62;

$app->get('/', function ($req, $res, $args) {
    $res->write('Welcome API Rest');
    return $res;
});

$app->get("/info", function ($request, $response, $arguments) {
    phpinfo();
});

$app->post("/token", function ($request, $response, $arguments) {
    $requested_scopes = $request->getParsedBody() ?: [];

    $now = new DateTime();
    //$future = new DateTime("+10 minutes");
    $future = new DateTime("now +24 hours");
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti,
        "sub" => $server["PHP_AUTH_USER"]
    ];
    //
    $secret = getenv("JWT_SECRET");
    $token = JWT::encode($payload, $secret, "HS256");

    $data["token"] = $token;
    $data["expires"] = $future->getTimeStamp();
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

/* This is just for debugging, not usefull in real life. */
$app->get("/dump", function ($request, $response, $arguments) {
    print_r($this->token);
});
$app->post("/dump", function ($request, $response, $arguments) {
    print_r($this->token);
});