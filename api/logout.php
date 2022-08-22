<?php

header("Content-Type: application/json; charset=UTF-8");

include_once("../database/DB.php");
include_once("../dbobjects/User.php");
include_once ('Auth.php');

$db = new DB();
$pdo = $db->createPDO();

$user = new User($pdo);
$auth = new Auth();

$tokenUserId = $auth->validateToken();

if ($tokenUserId) {
    http_response_code(200);
    echo json_encode(array(
        "message" => "User logged out"
    ));
} else {
    http_response_code(500);
    echo "Something went wrong";
    exit;
}