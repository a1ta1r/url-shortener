<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:40
 */

namespace Shortener\Controllers;


use Shortener\Repositories\ClickRepository;
use Shortener\Repositories\LinkRepository;
use Shortener\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ClickController extends BaseController
{
    private $linkRepository;
    private $clickRepository;

    public function __construct(
        UserRepository $userRepository,
        LinkRepository $linkRepository,
        ClickRepository $clickRepository
    )
    {
        parent::__construct($userRepository);
        $this->linkRepository = $linkRepository;
        $this->clickRepository = $clickRepository;

    }

    public function getReferers(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getUserByBasicAuth($request);
        if ($user === false) {
            return $this->authRequired();
        }
        $link = $this->linkRepository->getLinkById($id);
        if ($link === false) {
            return $this->notFound();
        }
        if ($link->user_id != $user->id) {
            return $this->authRequired();
        }
        $referers = $this->clickRepository->getLinkTopReferers($link->id);
        return new JsonResponse($referers, Response::HTTP_OK);
    }

    public function getDaysReport(Request $request)
    {
        return $this->getReport($request, 'days');
    }

    public function getHoursReport(Request $request)
    {
        return $this->getReport($request, 'hours');
    }

    public function getMinReport(Request $request)
    {
        return $this->getReport($request, 'min');
    }

    private function getReport(Request $request, $reportType)
    {
        $link_id = $request->get('id');
        $user = $this->getUserByBasicAuth($request);
        if ($user == false) {
            return $this->authRequired();
        }
        $link = $this->linkRepository->getLinkById($link_id);
        if ($link == false) {
            return $this->notFound();
        }
        if ($link->user_id != $user->id) {
            return $this->authRequired();
        }
        $from = strtotime($request->get('from_date'));
        $to = strtotime($request->get('to_date'));
        $report = $this->clickRepository->getReportOnLinkClickCount($link_id, $from, $to, $reportType);
        return new JsonResponse($report, Response::HTTP_OK);
    }

}