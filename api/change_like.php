<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/like.php';

$database = new Database();
$db = $database->getConnection();

$like = new Like($db);
$id_like = null;

$_POST = json_decode(file_get_contents('php://input'), true);

$id_music = $_POST['id_music'];
$id_user = $_POST['id_user'];

if (isset($id_music) && isset($id_user))
{
    $id_like = $like->get_state_like($id_music, $id_user);

    if ($id_like > 0){
        $result = $like->remove($id_like);
        var_dump ($result);
    }
    else if ($id_like == null) {
        $result = $like->insert($id_music, $id_user);
        var_dump ($result);  
    }
    
    http_response_code(200);
    echo json_encode(array("message" => "Все хорошо!"));
}
else
{
    echo json_encode(array("message" => "Параметры переданы не правильно!"));
}