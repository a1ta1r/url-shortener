<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:34
 */

namespace Shortener\Repositories;


abstract class BaseRepository
{
    private $db;

    public function __construct(\PDO $conn)
    {
        $this->db = $conn;
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }
}