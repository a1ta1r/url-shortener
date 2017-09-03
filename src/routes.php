<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 03.09.2017
 * Time: 20:56
 */

$users = $app['controllers_factory'];
$users->post('/', 'user.controller:register');
$users->get('/me', 'user.controller:getUserInfo');
$app->mount('api/v1/users', $users);

$links = $app['controllers_factory'];
$links->get('/', 'link.controller:getUserLinks');
$links->post('/', 'link.controller:addLink');
$links->get('/{id}', 'link.controller:getInfo');
$links->delete('/{id}', 'link.controller:deleteLink');
$app->mount('api/v1/users/me/shorten_urls', $links);
$app->get('/api/v1/shorten_urls/{hash}', 'link.controller:redirect');

$reports = $app['controllers_factory'];
$reports->get('/referers', 'click.controller:getReferers');
$reports->get('/days', 'click.controller:getDaysReport');
$reports->get('/hours', 'click.controller:getHoursReport');
$reports->get('/min', 'click.controller:getMinReport');
$app->mount('/api/v1/users/me/shorten_urls/{id}', $reports);