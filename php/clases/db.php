<?php

include '../config.php';

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Manejador de la base de datos
    private $error;

    public function __construct() {
        // Configurar la conexión DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Crear una instancia de PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }

    // Método para obtener la conexión
    public function getConnection() {
        return $this->dbh;
    }
}

