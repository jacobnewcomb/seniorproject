<?php

/* 
  generic code adapted from 
  https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
*/

// Singleton to connect db.
class ConnectDb
{
    // Hold the class instance.
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'senior';
    private $pass = 'project';
    private $name = 'seniorproject';

    // The db connection is established in the private constructor.
    private function __construct()
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->name);
        if (!$this->conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }
        return self::$instance;
    }
    public function getConnection()
    {
        return $this->conn;
    }
}
