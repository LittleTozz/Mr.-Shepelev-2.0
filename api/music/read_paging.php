<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключение файлов 
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/music.php';

// utilities 
$utilities = new Utilities();

// создание подключения 
$database = new Database();
$db = $database->getConnection();

// инициализация объекта 
$product = new Music($db);

// запрос товаров 
$stmt = $product->readPaging($from_record_num, $records_per_page, $category);
$num = $stmt->rowCount();

// если больше 0 записей 
if ($num>0) {

    // массив товаров 
    $products_arr=array();
    $products_arr["records"]=array();
    $products_arr["paging"]=array();

    // получаем содержимое нашей таблицы 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечение строки 
        extract($row);

        $product_item=array(
            "id_music" => $id_music,
            "name" => $name,
            "id_category" => $id_category,
            "duration" => $duration,
            "genre" => $genre,
            "path" => $path,
            "date" => $date
        );

        array_push($products_arr["records"], $product_item);
    }

    // подключим пагинацию 
    $total_rows=$product->count($category);
    $page_url="{$home_url}music/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"]=$paging;

    // установим код ответа - 200 OK 
    http_response_code(200);

    // вывод в json-формате 
    echo json_encode($products_arr);
} else {

    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // сообщим пользователю, что товаров не существует 
    echo json_encode(array("message" => "Музыка закончилась."), JSON_UNESCAPED_UNICODE);
}
?>