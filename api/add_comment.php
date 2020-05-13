<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/comment.php';

$database = new Database();
$db = $database->getConnection();

$comment = new Comment($db);

$_POST = json_decode(file_get_contents('php://input'), true);
$text = $_POST['text'];
$id_music = $_POST['id_music'];
$id_user = $_POST['id_user'];
$response = $_POST['response'];

if (isset($text) && isset($id_music) && isset($id_user))
{
    $result = $comment->insert($text, $id_music, $id_user, $response);
    var_dump ($result);
    
    http_response_code(200);
    echo json_encode(array("message" => "Все хорошо!"));
}
else
{
    echo json_encode(array("message" => $response));
}