<?php

include_once("DB.php");
include_once("dbobjects/User.php");

$db = new DB();
$pdo = $db->createPDO();

$data = json_decode(file_get_contents('php://input'));
$user = new User($data->name, $data->surname, $data->username,
            password_hash($data->password, PASSWORD_BCRYPT), $pdo);

if (empty($data->name) || empty($data->surname) || empty($data->username) ||
    empty($data->password)) {
    http_response_code(400);
    echo "Введены неверные данные" . "\n";
} else if (!$user->isUsernameExists($pdo)) {
    http_response_code(400);
    echo "Пользователь с таким username уже существует" . "\n";
} else if (!$user->createUser($pdo)) {
    http_response_code(500);
    echo "Невозможно создать пользователя" . "\n";
} else {
    http_response_code(200);
    echo "Пользователь успешно зарегистрирован";
}
