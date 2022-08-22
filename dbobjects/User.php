<?php

class User
{
    public $pdo;
    public $id;
    public $name;
    public $surname;
    public $username;
    public $password;
    public $roleId;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createUser() : bool {
        $sql = /** @lang text */
            "INSERT INTO users (name, surname, username, password, roleId) 
                    values (:name, :surname, :username, :password, :roleId)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password",  password_hash($this->password, PASSWORD_BCRYPT));
        $stmt->bindParam(":roleId", $this->roleId);

        if (!$stmt->execute()) {
            return false;
        }

        return true;
    }

    public function getUserId() {
        $sql = "select id from users WHERE username = ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $this->username);
        $stmt->execute();

        $this->id = $stmt->fetch()["id"];
    }

    public function getUserData($id) {
        $sql = "select * from (SELECT id, name, surname, username, password FROM users WHERE id = ?) u LIMIT 0,1";

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

    public function updateUserData($id, $name, $surname, $password) {
        $sql = "SELECT id, name, surname, username, password FROM users WHERE id = ? LIMIT 0,1";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();

        $sql = "UPDATE users SET name=?, surname=?, password=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name ?: $row["name"], $surname ?: $row["surname"],
            $password ? password_hash($password, PASSWORD_BCRYPT) : $row["password"], $id]);
    }

    public function isUsernameExists() : bool {
        $sql = "SELECT username from users";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            if ($row['username'] == $this->username) {
                return true;
            }
        }

        return false;
    }

    public function loginUser() {
        $sql = "SELECT id, name, surname, username, password from users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $this->username);
        $stmt->execute();

        $row = $stmt->fetch();
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->surname = $row['surname'];
        $this->password = $row['password'];
    }
}