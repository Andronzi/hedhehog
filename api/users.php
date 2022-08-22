<?php

header("Content-Type: application/json; charset=UTF-8");

include_once('../dbobjects/User.php');
include_once('../database/DB.php');
include_once('Auth.php');

$db = new DB();
$pdo = $db->createPDO();
$user = new User($pdo);
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['id'];

    if (empty($userId)) {
        http_response_code(400);
        echo "Введены неверные данные";
        exit;
    }

    $tokenUserId = $auth->validateToken();

    if ($tokenUserId == $userId) {
        $user->getUserData($userId);
    } else {
        http_response_code(401);
        echo "Доступ запрещен";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $userId = $_GET['id'];

    if (empty($userId)) {
        http_response_code(400);
        echo "Введены неверные данные";
        exit;
    }

    $tokenUserId = $auth->validateToken();
    $data = json_decode(file_get_contents('php://input'));

    if ($tokenUserId == $userId) {
        $user->updateUserData($userId, $data->name, $data->surname, $data->password);
        echo "Данные обновлены";
    } else {
        http_response_code(401);
        echo "Доступ запрещен";
    }
}