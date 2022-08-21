<?php

class DB {
    private $host = "localhost";
    private $port = 3306;
    private $dbname = "hedgehog";
    private $username = 'root';
    private $password = '1234';

    public function createPDO() {
        $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf-8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        $pdo = null;

        try {
            $pdo =  new PDO($dsn, $this->username, $this->password, $options);
            echo "Подключение к $this->dbname прошло успешно";
        } catch (PDOException $error) {
            echo $error->getMessage();
        }

        return $pdo;
    }
}