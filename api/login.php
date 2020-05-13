<?php
// требуемые заголовки 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');
header("HTTP/1.1 200 OK");
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}
 
// файлы необходимые для соединения с БД 
include_once 'config/database.php';
include_once 'objects/user.php';
 
// получаем соединение с базой данных 
$database = new Database();
$db = $database->getConnection();
 
// создание объекта 'User' 
$user = new User($db);
 
// получаем данные 
$data = json_decode(file_get_contents("php://input"));
 
// устанавливаем значения 
$user->email = $data->email;
$email_exists = $user->emailExists();
 
// существует ли электронная почта и соответствует ли пароль тому, что находится в базе данных 
if ( $email_exists && password_verify($data->password, $user->password) ) {
 
    $user = array(
        "id" => $user->id,
        "login" => $user->login,
        "email" => $user->email,
        "img_account" => $user->img_account
    );
 
    // код ответа 
    http_response_code(200);
 
    // создание jwt 
    echo json_encode(
        array(
            "user" => $user
        )
    );
 
}
 
// Если электронная почта не существует или пароль не совпадает, 
// сообщим пользователю, что он не может войти в систему 
else {
 
  // код ответа 
  http_response_code(401);

  // сказать пользователю что войти не удалось 
  echo json_encode(array("message" => "Ошибка входа."));
}