<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 04.09.2017
 * Time: 23:26
 */

use Shortener\Repositories\ClickRepository;

class ClickRepositoryTest extends BaseTest
{
    /**
     * @param $lid
     * @param $count
     * @dataProvider getClicksData
     */
    public function testGetClicks($lid, $count, $success)
    {
        Self::$repo = new ClickRepository(Self::$pdo);
        $clicks = Self::$repo->getClicksByLinkId($lid);
        if ($success) {
            $this->assertEquals($count, count($clicks));
        } else {
            $this->assertNotEquals($count, $clicks);
        }
    }

    public function getClicksData()
    {
        return [
            [20, 8, true],
            [21, 2, true],
            [19, 15, false],
            [20, 9, false],
            [27, 1, false]
        ];
    }
}
