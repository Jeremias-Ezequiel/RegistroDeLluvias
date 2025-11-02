<?php

class Database
{
    private $server = "localhost";
    private $user = "root";
    private $pass = "abcdef2020";
    private $db = "lluvias";
    private $con;

    public function __construct()
    {
        $this->con = null;

        try {
            $dsn = "mysql:host=$this->server;dbname=$this->db";
            $this->con = new PDO($dsn, $this->user, $this->pass);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en el servidor: " . $e->getMessage());
        }
    }

    public function getCon()
    {
        return $this->con;
    }
}
