<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:39
 */

namespace Shortener\Controllers;


use Shortener\Repositories\LinkRepository;
use Shortener\Repositories\UserRepository;
use Shortener\Repositories\ClickRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LinkController extends BaseController
{
    private $linkRepository;
    private $clickRepository;

    public function __construct(
        UserRepository $userRepository,
        LinkRepository $linkRepository,
        ClickRepository $clickRepository)
    {
        parent::__construct($userRepository);
        $this->linkRepository = $linkRepository;
        $this->clickRepository = $clickRepository;
    }

    public function addLink(Request $request)
    {
        $full_link = $request->get('full_link');
        $user = $this->getUserByBasicAuth($request);
        if ($user == false) {
            return $this->authRequired();
        }
        $link = $this->linkRepository->addLink($user->id, $full_link);
        return new JsonResponse(
            [
                'full_link' => $link->fullLink,
                'short_link' => $link->shortLink
            ], Response::HTTP_CREATED
        );
    }

    public function getInfo(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getUserByBasicAuth($request);
        if ($user == false) {
            return $this->authRequired();
        }
        $link = $this->linkRepository->getLinkById($id);
        if ($link == false) {
            return new JsonResponse(
                null, Response::HTTP_NOT_FOUND
            );
        }
        if ($link->user_id == $user->id) {
            $clickCount = count($this->clickRepository->getClicksByLinkId($link->id));
            return new JsonResponse(
                [
                    'id' => $link->id,
                    'user_id' => $link->user_id,
                    'full_link' => $link->fullLink,
                    'short_link' => $link->shortLink,
                    'clicks' => $clickCount
                ], Response::HTTP_OK
            );
        } else {
            return $this->authRequired();
        }
    }

    public function deleteLink(Request $request)
    {
        $user = $this->getUserByBasicAuth($request)->id;
        if ($user == false) {
            return $this->authRequired();
        }
        $id = $request->get('id');
        if ($this->linkRepository->deleteLinkById($id)) {
            return new JsonResponse(
                null, Response::HTTP_OK
            );
        } else {
            return new JsonResponse(
                null, Response::HTTP_NOT_FOUND
            );
        }
    }

    public function getUserLinks(Request $request)
    {
        $user = $this->getUserByBasicAuth($request);
        if ($user == false) {
            return $this->authRequired();
        }
        $links = $this->linkRepository->getLinksByUserId($user->id);
        if ($links !== false) {
            return new JsonResponse(
                $links, Response::HTTP_OK
            );
        } else {
            return new JsonResponse(
                null, Response::HTTP_NOT_FOUND
            );
        }
    }

    public function redirect(Request $request)
    {
        $url = $request->get('hash');
        $referer = parse_url($request->headers->get('Referer'), PHP_URL_HOST);
        $link = $this->linkRepository->getLinkByShortUrl($url);
        if ($link !== false) {
            $this->clickRepository->addClick($link->id, $referer);
            return new RedirectResponse($link->fullLink);
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }
}