<?php
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'internship_management';
    public $connection;

    public function __construct() {
        // Create connection
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function error() {
        return $this->connection->error;
    }

    public function close() {
        $this->connection->close();
    }
}
