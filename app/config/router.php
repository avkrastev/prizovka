<?php

use Phalcon\Mvc\Router;

// Create the router
$router = new Router();

$router->add(
    '/admin/login',
    [
        'controller' => 'session',
        'action'     => 'start',
    ]
);

$router->add(
    '/admin',
    [
        'controller' => 'index',
        'action'     => 'index',
    ]
);

$router->add(
    '/employees',
    [
        'controller' => 'employees',
        'action'     => 'index',
    ]
);

$router->add(
    '/addresses',
    [
        'controller' => 'addresses',
        'action'     => 'index',
    ]
);

$router->add(
    '/subpoenas',
    [
        'controller' => 'subpoenas',
        'action'     => 'index',
    ]
);

$router->add(
    '/statistics',
    [
        'controller' => 'statistics',
        'action'     => 'index',
    ]
);

$router->add(
    '/history',
    [
        'controller' => 'history',
        'action'     => 'index',
    ]
);

$router->handle();