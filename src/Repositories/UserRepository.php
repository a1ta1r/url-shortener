<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:34
 */

namespace Shortener\Repositories;


use Shortener\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * @param $email
     * @param $name
     * @param $password
     * @return User
     */
    public function addUser($email, $name, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(null, $email, $name, $hash);
        $this->saveUser($user);
        return $user;
    }

    /**
     * @param User $user
     */
    public function saveUser(User &$user)
    {
        if ($user != null) {
            if ($user->id == 0) {
                $stmt = $this->getDb()->prepare("INSERT INTO Users (name, email, passhash) VALUES (:n, :e, :p)");
                $stmt->bindParam(':n', $user->name);
                $stmt->bindParam(':e', $user->email);
                $stmt->bindParam(':p', $user->passhash);
                $stmt->execute();
                $user->id = $this->getDb()->lastInsertId();
            } else {
                $stmt = $this->getDb()->prepare("UPDATE Users SET name = :n");
                $stmt->bindParam(':n', $user->name);
                $stmt->execute();
            }
        }
    }

    /**
     * @param $email
     * @return bool|User
     */
    public function getUserByEmail($email)
    {
        $stmt = $this->getDb()->prepare("SELECT id, email, name, passhash FROM Users WHERE email = :e LIMIT 1");
        $stmt->bindParam(':e', $email);
        $stmt->execute();
        $user = $stmt->fetchAll();
        if ($user[0] != null) {
            return new User($user[0]['id'], $user[0]['email'], $user[0]['name'], $user[0]['passhash']);
        } else {
            return false;
        }
    }
}