<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$_POST = json_decode(file_get_contents('php://input'), true);

$id_user = $_POST['id_user'];
$url = $_POST['url'];

if (isset($id_user) && isset($url))
{
    $ansver = $user->update_image($id_user, $url);
    
    http_response_code(200);
    echo json_encode(array("message" => $ansver));
}
else
{
    echo json_encode(array("message" => "Параметры переданы не правильно!"));
}