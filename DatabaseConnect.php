<?php

class DatabaseConnect {
    private $mysql_host = 'localhost';
    private $username = 'root';
    private $password = 'abc123';
    private $database = 'cryptoXdb';
    private $pdo = null;

    public function getPdo(){
        return $this->pdo;
    }

    public function __construct(){
        try {
            $this->pdo = new PDO('mysql:host='.$this->mysql_host.';dbname='.$this->database.';charset=utf8', $this->username, $this->password );
        }
        catch(PDOException $e) {
            echo 'Połączenie nie mogło zostać utworzone.<br />';
        }
    }
}