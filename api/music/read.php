<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/music.php';

$database = new Database();
$db = $database->getConnection();

$music = new Music($db);
 
if ($_GET['category'] == 'track')
    $stmt = $music->read_track();
else if ($_GET['category'] == 'remix')
    $stmt = $music->read_remix();
else if ($_GET['category'] == 'all')
    $stmt = $music->read_all();
$num = $stmt->rowCount();

if ($num>0) {

    $music_arr=array();
    $music_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $music_item=array(
            "id_music" => $id_music,
            "name" => $name,
            "id_category" => $id_category,
            "duration" => $duration,
            "genre" => $genre,
            "path" => $path,
            "date" => $date
        );

        array_push($music_arr["records"], $music_item);
    }

    http_response_code(200);

    echo json_encode($music_arr);
}

else {

    http_response_code(404);

    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}