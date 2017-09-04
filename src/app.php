<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:41
 */

use Shortener\Controllers\UserController;
use Shortener\Controllers\LinkController;
use Shortener\Controllers\ClickController;
use Shortener\Repositories\UserRepository;
use Shortener\Repositories\LinkRepository;
use Shortener\Repositories\ClickRepository;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../etc/Connection.php';

$app['db'] = Connection::getPDO();


$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->before(function (Request $request, $app) {
    if ($app['db'] == false)
        return new \Symfony\Component\HttpFoundation\JsonResponse(
            ['error' => 'could not connect to database'],
            \Symfony\Component\HttpFoundation\Response::HTTP_SERVICE_UNAVAILABLE
        );
    if (strpos($request->headers->get('Content-Type'), 'application/json') == 0) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app['user.controller'] = function ($app) {
    return new UserController($app['user.repository']);
};

$app['user.repository'] = function ($app) {
    return new UserRepository($app['db']);
};

$app['click.controller'] = function ($app) {
    return new ClickController($app['user.repository'], $app['link.repository'], $app['click.repository']);
};

$app['click.repository'] = function ($app) {
    return new ClickRepository($app['db']);
};

$app['link.controller'] = function ($app) {
    return new LinkController($app['user.repository'], $app['link.repository'], $app['click.repository']);
};

$app['link.repository'] = function ($app) {
    return new LinkRepository($app['db']);
};


