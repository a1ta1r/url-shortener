<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 04.09.2017
 * Time: 20:58
 */


use Shortener\Repositories\LinkRepository;

class LinkRepositoryTest extends BaseTest
{
    /**
     * @param $uid
     * @param $link
     * @dataProvider addLinkData
     */
    public function testAddLink($uid, $link, $success)
    {
        Self::$repo = new LinkRepository(Self::$pdo);
        $link = Self::$repo->addLink($uid, $link);
        if ($success) {
            $this->assertEquals($uid, $link->user_id);
        } else {
            $this->assertEquals(false, $link);
        }
    }

    /**
     * @param $id
     * @param $success
     * @dataProvider getAndDeleteLinkData
     */
    public function testGetLink($id, $success)
    {
        Self::$repo = new LinkRepository(Self::$pdo);
        $link = Self::$repo->getLinkById($id);
        if ($success) {
            $this->assertEquals($id, $link->id);
        } else {
            $this->assertEquals(false, $link);
        }
    }

    /**
     * @param $id
     * @param $success
     * @dataProvider getAndDeleteLinkData
     */
    public function testDeleteLink($id, $success)
    {
        Self::$repo = new LinkRepository(Self::$pdo);
        $link = Self::$repo->deleteLinkById($id);
        if ($success) {
            $this->assertEquals($id, $link->id);
        } else {
            $this->assertEquals(false, $link);
        }
    }

    public function addLinkData()
    {
        return [
            [9, 'https://vk.com', true],
            [10, 'https://vk.com', true],
            [11, null, false],
            [12, 'https://vk.com', false],
        ];
    }

    public function getAndDeleteLinkData()
    {
        return [
            [20, true],
            [21, true],
            [22, false],
            ['abcde', false]
        ];
    }
}
