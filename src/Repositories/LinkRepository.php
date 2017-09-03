<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:37
 */

namespace Shortener\Repositories;


use Shortener\Models\Link;

class LinkRepository extends BaseRepository
{
    public function addLink($user_id, $full_link)
    {
        $link = new Link(0, $user_id, $full_link, '');
        $this->saveLink($link);
        return $link;
    }

    public function getLinkById($id)
    {
        $stmt = $this->getDb()->prepare("SELECT id, user_id, full_link, short_link FROM Links WHERE id = :i LIMIT 1");
        $stmt->bindParam(':i', $id);
        $stmt->execute();
        $link = $stmt->fetchAll();
        if ($link[0] != null) {
            return new Link($link[0]['id'], $link[0]['user_id'], $link[0]['full_link'], $link[0]['short_link']);
        } else {
            return false;
        }
    }

    public function getLinksByUserId($user_id)
    {
        $stmt = $this->getDb()->prepare("SELECT id, user_id, full_link, short_link FROM Links WHERE user_id = :ui");
        $stmt->bindParam(':ui', $user_id);
        $stmt->execute();
        $raw = $stmt->fetchAll();
        if ($raw[0]['id'] != null) {
            $links = array();
            foreach ($raw as $link) {
                $links[] = new Link($link['id'], $link['user_id'], $link['full_link'], $link['short_link']);
            }
            return $links;
        } else {
            return false;
        }
    }

    public function getLinkByShortUrl($url)
    {
        $stmt = $this->getDb()->prepare("SELECT id, user_id, full_link, short_link FROM Links WHERE short_link = :sl LIMIT 1");
        $stmt->bindParam(':sl', $url);
        $stmt->execute();
        $link = $stmt->fetchAll();
        if ($link[0] !== null) {
            return new Link($link[0]['id'], $link[0]['user_id'], $link[0]['full_link'], $link[0]['short_link']);
        } else {
            return false;
        }
    }

    public function saveLink(Link &$link)
    {
        if ($link != null) {
            if ($link->id === 0) {
                $stmt = $this->getDb()->prepare("INSERT INTO Links (user_id, full_link, short_link) VALUES (:ui, :fl, :sl)");
                $stmt->bindParam(':ui', $link->user_id);
                $stmt->bindParam(':fl', $link->full_link);
                $stmt->bindParam(':sl', $link->short_link);
                $stmt->execute();
                $link->id = $this->getDb()->lastInsertId();
                $link->short_link = $this->generateShortLinkById($link->id);
                $this->saveLink($link);
            } else {
                $stmt = $this->getDb()->prepare("UPDATE Links SET short_link = :sl WHERE id = :i");
                $stmt->bindParam(':sl', $link->short_link);
                $stmt->bindParam(':i', intval($link->id));
                $stmt->execute();
            }
        }
    }

    public function deleteLinkById($id)
    {
        $link = $this->getLinkById($id);
        if ($link !== false) {
            $stmt = $this->getDb()->prepare("DELETE FROM Links WHERE id = :i LIMIT 1");
            $stmt->bindParam(':i', $id);
            $stmt->execute();
            return true;
        } else {
            return false;
        }
    }

    public function generateShortLinkById($id)
    {
        $symbols = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
        $short_link = '';
        if ($id != null) {
            while ($id > 0) {
                $mod = $id % 62;
                $short_link .= $symbols[$mod];
                $id = intdiv($id, 62);
            }
            return $short_link;
        }
        return null;
    }
}