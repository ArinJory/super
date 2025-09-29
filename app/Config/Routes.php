<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/', 'Superheroes::index');
$routes->get('/api/superheroes', 'Superheroes::apiSuperheroes');
$routes->get('/api/aggregate', 'Superheroes::apiAggregate');
$routes->get('testdb', 'TestDb::index');
$routes->get('reporte/pdf', 'ReporteController::pdf');



