<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:39
 */

namespace Shortener\Controllers;

use Shortener\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function getUserByBasicAuth(Request $request)
    {
        $email = $request->server->get('PHP_AUTH_USER');
        if ($email == null) {
            header('WWW-Authenticate: Basic realm="Url Shortener"');
        }
        $password = $request->server->get('PHP_AUTH_PW');
        $user = $this->userRepository->getUserByEmail($email);
        if ($user !== false && password_verify($password, $user->passhash)) {
            return $user;
        } else {
            return false;
        }
    }

    protected function authRequired()
    {
        return new JsonResponse(
            ['error' => 'not authorized'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    protected function error($ex)
    {
        return new JsonResponse(
            ['error' => $ex],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}