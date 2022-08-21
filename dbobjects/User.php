<?php

class User
{
    public $pdo;
    public $name;
    public $surname;
    public $username;
    public $password;

    public function __construct($name, $surname, $username, $password) {
        $this->name = $name;
        $this->surname = $surname;
        $this->username = $username;
        $this->password = $password;
    }

    public function createUser($pdo) : bool {
        $sql = "INSERT INTO users (name, surname, username, password) 
                    values (:name, :surname, :username, :password)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function isUsernameExists($pdo) : bool {
        $sql = "SELECT username from users";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            if ($row['username'] == $this->username) {
                return false;
            }
        }

        return true;
    }
}