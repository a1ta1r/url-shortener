<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 04.09.2017
 * Time: 23:09
 */

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

abstract class BaseTest extends TestCase
{
    use TestCaseTrait;

    protected static $pdo = null;
    protected static $repo = null;

    protected function getConnection()
    {
        if (Self::$pdo == null) {
            Self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        }

        return $this->createDefaultDBConnection(Self::$pdo, $GLOBALS['DB_DBNAME']);
    }

    protected function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/files/dataSet.xml');
    }
}