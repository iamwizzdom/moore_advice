<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 7/16/2020
 * Time: 9:37 PM
 */

class Connect
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli('localhost', 'root', '', 'moore_advice');
    }

    protected function exec(string $sql) {
        return $this->mysqli->query($sql);
    }

    protected function getLastID() {
        return $this->mysqli->insert_id;
    }

    protected function getError() {
        return $this->mysqli->error;
    }
}
