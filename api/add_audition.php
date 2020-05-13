<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/audition.php';

$database = new Database();
$db = $database->getConnection();

$audition = new Audition($db);

$_POST = json_decode(file_get_contents('php://input'), true);
$id_music = $_POST['id_music'];
$id_user = $_POST['id_user'];
$time = $_POST['time'];

if (isset($id_music) && isset($time))
{
    if (isset($id_user))
        $result = $audition->insert($id_music, $id_user, $time);
    else {
        $result = $audition->insert($id_music, 'null', $time);
    }
    var_dump ($result);
    
    http_response_code(200);
    echo json_encode(array("message" => "Все хорошо!"));
}
else
{
    echo json_encode(array("message" => $id_user));
}