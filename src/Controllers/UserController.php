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

class UserController extends BaseController
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function register(Request $request)
    {
        $email = $request->get('email');
        $name = $request->get('name');
        $password = $request->get('password');
        if ($email == null || $name == null || $password == null) {
            return $this->error('Insufficient data. Email, name and password required');
        }
        try {
            $user = $this->userRepository->addUser($email, $name, $password);
        } catch (\PDOException $ex) {
            return $this->error('Email already exists.');
        }
        return new JsonResponse(
            [
                'email' => $user->email,
                'name' => $user->name
            ],
            Response::HTTP_CREATED
        );
    }

    public function getUserInfo(Request $request)
    {
        $user = $this->getUserByBasicAuth($request);
        if ($user == false) {
            return $this->authRequired();
        }
        return new JsonResponse(
            [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ],
            Response::HTTP_OK
        );
    }
}