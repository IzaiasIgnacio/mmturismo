<?php

class database {

    private $host = "localhost";
	private $usuario = "root";
    private $senha = "";
    private $banco = "mmturismo";
    private $db;

    function __construct() {
        $this->db = new \PDO("mysql:host=".$this->host.";dbname=".$this->banco, $this->usuario, $this->senha, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_PERSISTENT, true);
    }

    public function query($sql, $binds = null) {
        $sth = $this->db->prepare($sql);
        $sth->execute($binds);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function execute($sql, $binds = null) {
        try {
            $sth = $this->db->prepare($sql);
            return $sth->execute($binds);
        }
        catch (\PDOException $e) {
            // $this->rollback();
            echo $sql;
            print_r($binds);
            throw $e;
        }
    }

    public function begin() {
        $this->db->beginTransaction();
    }

    public function commit() {
        $this->db->commit();
    }

    public function rollback() {
        $this->db->rollback();
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

}