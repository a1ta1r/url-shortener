<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 04.09.2017
 * Time: 20:58
 */

use Shortener\Repositories\UserRepository;

class UserRepositoryTest extends BaseTest
{
    /**
     * @dataProvider addUserData
     */
    public function testAddUser($mail, $name, $pw, $success)
    {
        Self::$repo = new UserRepository(Self::$pdo);
        $count = $this->getConnection()->getRowCount('Users');
        try {
            Self::$repo->addUser($mail, $name, $pw);
        } catch (Exception $ex) {
        }
        if ($success) {
            $this->assertEquals($count + 1, $this->getConnection()->getRowCount('Users'));
        } else {
            $this->assertEquals($count, $this->getConnection()->getRowCount('Users'));
        }
    }

    /**
     * @dataProvider getUserData
     */
    public function testGetUser($mail, $success)
    {
        Self::$repo = new UserRepository(Self::$pdo);
        $user = Self::$repo->getUserByEmail($mail);
        if ($success) {
            $this->assertEquals($mail, $user->email);
        } else {
            $this->assertEquals(false, $user);
        }
    }

    public function addUserData()
    {
        return [
            ['Ivan@yandex.ru', 'Ivan', 'qwerty', true],
            [null, 'Ivan', 'qwerty', false],
            ['Maria@yandex.ru', 'Maria', 'qwerty', true]
        ];
    }

    public function getUserData()
    {
        return [
            ['John@mail.ru', true],
            ['Ann@mail.ru', true],
            ['Ivan@mail.ru', true],
            ['fail@mail.ru', false]
        ];
    }
}
