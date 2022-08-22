<?php

header("Content-Type: application/json; charset=UTF-8");

include_once("../database/DB.php");
include_once("../dbobjects/User.php");
include_once("Auth.php");

$db = new DB();
$pdo = $db->createPDO();
$auth = new Auth();

$data = json_decode(file_get_contents('php://input'));
$user = new User($pdo);
$user->name = $data->name;
$user->surname = $data->surname;
$user->username = $data->username;
$user->password = $data->password;
$user->roleId = $data->roleId;
$user->getUserId();

if (empty($data->name) || empty($data->surname) || empty($data->username) ||
    empty($data->password)) {
    http_response_code(400);
    echo "Введены неверные данные" . "\n";
} else if ($user->isUsernameExists()) {
    http_response_code(400);
    echo "Пользователь с таким username уже существует" . "\n";
} else if (!$user->createUser()) {
    http_response_code(500);
    echo "Невозможно создать пользователя" . "\n";
} else {
    http_response_code(200);
    echo json_encode(
        array (
            "message" => "Регистрация прошла успешно",
            "token" => $auth->createToken($user->id)
        )
    );
}
