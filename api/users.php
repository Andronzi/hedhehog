<?php

include_once('../dbobjects/User.php');
include_once('../database/DB.php');
include_once('Auth.php');

$db = new DB();
$pdo = $db->createPDO();
$user = new User($pdo);
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['id'];

    if (!empty($userId) && $auth->validateToken()) {
        $user->getUserData();
    }
}