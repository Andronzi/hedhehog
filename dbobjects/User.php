<?php

class User
{
    public $pdo;
    public $name;
    public $surname;
    public $username;
    public $password;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createUser() : bool {
        $sql = "INSERT INTO users (name, surname, username, password) 
                    values (:name, :surname, :username, :password)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password",  password_hash($this->password, PASSWORD_BCRYPT));

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getUserData($id) {
        $sql = "SELECT (name, surname, username, password) from users WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();

        echo json_encode(
            array (
                "userId" => $row['id'],
                "username" => $row['username'],
                "name" => $row['name'],
                "surname" => $row['surname'],
            )
        );
    }

    public function isUsernameExists() : bool {
        $sql = "SELECT username from users";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            if ($row['username'] == $this->username) {
                return false;
            }
        }

        return true;
    }
}