<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.08.2017
 * Time: 16:45
 */

class Connection
{
    public static function getPDO()
    {
        $host = 'localhost';
        $dbname = 'UrlShortenerDB';
        $dsn = "mysql:host=$host;dbname=$dbname";
        $username = 'root';
        $password = 'root';
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

