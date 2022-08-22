<?php

header("Content-Type: application/json; charset=UTF-8");

include_once("../database/DB.php");
include_once("../dbobjects/User.php");
include_once ('Auth.php');

$db = new DB();
$pdo = $db->createPDO();
$user = new User($pdo);
$auth = new Auth();

$data = json_decode(file_get_contents('php://input'));

if (empty($data->username) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(
        [
            "message" => "Введены неверные данные"
        ]
    );
    exit;
}

$user->username = $data->username;
$user->loginUser();

if (password_verify($data->password, $user->password)) {
    http_response_code(200);
    echo json_encode(
        [
            "message" => "Вы успешно вошли в аккаунт",
            "token" => $auth->createToken($user->id)
        ]
    );
} else {
    http_response_code(401);
    echo json_encode(
        [
            "message" =>  "Ошибка входа",
        ]
    );
}