<?php
/**
 * Created by PhpStorm.
 * User: Ricoru
 * Date: 26/12/17
 * Time: 15:38
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/hello[/{name}]', function ($request, $response, $args) {
    //return $response->getBody()->write("Hello, " . $args['name']);
    return $response->write("Hello " . $args['name']);
});

$app->get('/test/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Mi Nombre es $name");
    return $response;
});

/*$app->get('/tickets', function (Request $request, Response $response) {
    $this->logger->addInfo("Ticket list");
    $mapper = new TicketMapper($this->db);
    $tickets = $mapper->getTickets();

    $response->getBody()->write(var_export($tickets, true));
    return $response;
});

$app->get('/ticket/{id}', function (Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];
    $mapper = new TicketMapper($this->db);
    $ticket = $mapper->getTicketById($ticket_id);

    $response->getBody()->write(var_export($ticket, true));
    return $response;
});*/

$app->get('/foo', function ($req, $res, $args) {
    $this->logger->addInfo("this foo");
    $res->withHeader('Content-Type', 'application/json');
    $res->withStatus(200)->write(' Hello World! ');
    return $res;
})->add($mw);