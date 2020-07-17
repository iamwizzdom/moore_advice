<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 7/16/2020
 * Time: 9:38 PM
 */

require 'Connect.php';

class DB extends Connect
{
    private static $instance;

    private static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    public static function write(string $sql) {
        if (!self::getInstance()->exec($sql)) return false;
        return self::getInstance()->getLastID();
    }

    public static function read(string $sql)
    {
        $conn = self::getInstance();
        $result = $conn->exec($sql);
        $records = [];
        if ($result && $result->num_rows > 0)
            while ($row = $result->fetch_object()) $records[] = $row;
        return $records;
    }

    public static function error()
    {
        return self::getInstance()->getError();
    }
}
