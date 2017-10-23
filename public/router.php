<?php

$router->add('/:controller/:action', [
    'module'     => 'frontend',
    'controller' => 1,
    'action'     => 2,
])->setName('frontend');

$router->add("/admin", [
    'module'     => 'backend',
    'controller' => 'login',
    'action'     => 'index',
])->setName('backend-login');

$router->add("/admin/products", [
    'module'     => 'backend',
    'controller' => 'products',
    'action'     => 'index',
])->setName('backend-product');

$router->add("/products/:action", [
    'module'     => 'frontend',
    'controller' => 'products',
    'action'     => 1,
])->setName('frontend-product');