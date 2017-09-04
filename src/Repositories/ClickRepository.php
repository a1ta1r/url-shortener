<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:37
 */

namespace Shortener\Repositories;


use Shortener\Models\Click;

class ClickRepository extends BaseRepository
{
    private $week = 604800;
    private $day = 86400;
    private $hour = 3600;
    private $minute = 60;

    /**
     * @param $link_id
     * @param $referer
     * @return Click
     */
    public function addClick($link_id, $referer)
    {
        $click = new Click(null, $link_id, time(), $referer);
        $date = date('Y-m-d H:i:s', $click->click_time);
        $stmt = $this->getDb()->prepare("INSERT INTO Clicks (link_id, click_time, referer) VALUES (:li, :ct, :r)");
        $stmt->bindParam(':li', $click->link_id);
        $stmt->bindParam(':ct', $date);
        $stmt->bindParam(':r', $click->referer);
        $stmt->execute();
        $click->id = $this->getDb()->lastInsertId();
        return $click;
    }

    /**
     * @param $link_id
     * @return bool|array
     */
    public function getClicksByLinkId($link_id)
    {
        $stmt = $this->getDb()->prepare("SELECT id, link_id, click_time, referer FROM Clicks WHERE link_id = :li");
        $stmt->bindParam(':li', $link_id);
        $stmt->execute();
        $raw = $stmt->fetchAll();
        if (!isset($raw[0]['id'])) {
            return false;
        }
        $clicks = array();
        foreach ($raw as $c) {
            $clicks[] = new Click($c[0], $c[1], $c[2], $c[3]);
        }
        return $clicks;
    }

    /**
     * @param $link_id
     * @param $from
     * @param $to
     * @param $type
     * @return array|null
     */
    public function getReportOnLinkClickCount($link_id, $from, $to, $type)
    {
        if ($from > $to)
            return null;
        if ($to == null) {
            $to = time();
        }
        switch ($type) {
            case 'days':
                if ($from == null) {
                    $from = $to - $this->week;
                }
                break;
            case 'hours':
                if ($from == null) {
                    $from = $to - $this->day;
                }
                break;
            case 'min':
                if ($from == null) {
                    $from = $to - $this->hour;
                }
                break;
        }
        $stmt = $this->getDb()->prepare('SELECT click_time FROM Clicks WHERE link_id = :li');
        $stmt->bindParam(':li', $link_id);
        $stmt->execute();
        $raw = $stmt->fetchAll();
        $filteredClickTimes = array();

        switch ($type) {
            case 'days':
                foreach ($raw as $click) {
                    $clickTime = strtotime($click['click_time']);
                    $clickTime = strtotime(date('Y-m-d', $clickTime));
                    if ($clickTime >= $from && $clickTime <= $to) {
                        $clickDate = date('Y-m-d', $clickTime);
                        $filteredClickTimes[$clickDate]++;
                    }
                }
                return $this->countByDays($filteredClickTimes, $from, $to);
                break;
            case 'hours':
                foreach ($raw as $click) {
                    $clickTime = strtotime($click['click_time']);
                    $clickTime = date('Y-m-d H', $clickTime);
                    $clickTime = \DateTime::createFromFormat('Y-m-d H', $clickTime);
                    $clickTime = $clickTime->getTimestamp();

                    if ($clickTime >= $from && $clickTime <= $to + $this->day - $this->hour) {
                        $clickDate = date('Y-m-d H', $clickTime);
                        $filteredClickTimes[$clickDate]++;
                    }
                }
                return $this->countByHours($filteredClickTimes, $from, $to);
                break;
            case 'min':
                foreach ($raw as $click) {
                    $clickTime = strtotime($click['click_time']);
                    $clickTime = date('Y-m-d H:i', $clickTime);
                    $clickTime = \DateTime::createFromFormat('Y-m-d H:i', $clickTime);
                    $clickTime = $clickTime->getTimestamp();

                    if ($clickTime >= $from && $clickTime <= $to + $this->day - $this->minute) {
                        $clickDate = date('Y-m-d H:i', $clickTime);
                        $filteredClickTimes[$clickDate]++;
                    }
                }
                return $this->countByMin($filteredClickTimes, $from, $to);
                break;
        }
    }

    /**
     * @param $days
     * @param $from
     * @param $to
     * @return array
     */
    public function countByDays($days, $from, $to)
    {
        $count = array();
        for ($i = $from; $i <= $to; $i += $this->day) {
            $count[date('Y-m-d', $i)] = 0;
        }
        foreach (array_keys($days) as $day) {
            $count[$day] = $days[$day];
        }
        return $count;
    }

    /**
     * @param $hours
     * @param $from
     * @param $to
     * @return array
     */
    public function countByHours($hours, $from, $to)
    {
        $count = array();
        for ($i = $from; $i <= $to; $i += $this->hour) {
            $count[date('Y-m-d H', $i)] = 0;
        }
        foreach (array_keys($hours) as $hour) {
            $count[$hour] = $hours[$hour];
        }
        return $count;
    }

    /**
     * @param $mins
     * @param $from
     * @param $to
     * @return array
     */
    public function countByMin($mins, $from, $to)
    {
        $count = array();
        for ($i = $from; $i <= $to; $i += $this->minute) {
            $count[date('Y-m-d H:i', $i)] = 0;
        }
        foreach (array_keys($mins) as $min) {
            $count[$min] = $mins[$min];
        }
        return $count;
    }

    /**
     * @param $link_id
     * @return array
     */
    public function getLinkTopReferers($link_id)
    {
        $stmt = $this->getDb()->prepare("SELECT referer FROM Clicks WHERE link_id = :li");
        $stmt->bindParam(':li', $link_id);
        $stmt->execute();
        $referers = $stmt->fetchAll();

        $count = array();
        foreach ($referers as $ref) {
            $count[$ref[0]]++;
        }

        arsort($count);
        array_slice($count, 0, 20, true);

        return $count;
    }
}