<?php
// объект 'user' 
class User {
 
    // подключение к БД таблице "users" 
    private $conn;
    private $table_name = "users";
 
    // свойства объекта 
    public $id;
    public $login;
    public $email;
    public $password;
    public $img_account;
 
    // конструктор класса User 
    public function __construct($db) {
        $this->conn = $db;
    }

    // Создание нового пользователя 
    function create() {
    
        // Вставляем запрос 
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    login = :login,
                    email = :email,
                    password = :password";
    
        // подготовка запроса 
        $stmt = $this->conn->prepare($query);
    
        // инъекция 
        $this->login=htmlspecialchars(strip_tags($this->login));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
    
        // привязываем значения 
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':email', $this->email);
    
        // для защиты пароля 
        // хешируем пароль перед сохранением в базу данных 
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    
        // Выполняем запрос 
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных 
        if($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    
    // Проверка, существует ли электронная почта в нашей базе данных 
    function emailExists(){
 
        // запрос, чтобы проверить, существует ли электронная почта 
        $query = "SELECT id, login, password, img_account
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";
     
        // подготовка запроса 
        $stmt = $this->conn->prepare( $query );
     
        // инъекция 
        $this->email=htmlspecialchars(strip_tags($this->email));
     
        // привязываем значение e-mail 
        $stmt->bindParam(1, $this->email);
     
        // выполняем запрос 
        $stmt->execute();
     
        // получаем количество строк 
        $num = $stmt->rowCount();
     
        // если электронная почта существует, 
        // присвоим значения свойствам объекта для легкого доступа и использования для php сессий 
        if($num>0) {
     
            // получаем значения 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
            // присвоим значения свойствам объекта 
            $this->id = $row['id'];
            $this->login = $row['login'];
            $this->password = $row['password'];
            $this->img_account = $row['img_account'];
     
            // вернём 'true', потому что в базе данных существует электронная почта 
            return true;
        }
     
        // вернём 'false', если адрес электронной почты не существует в базе данных 
        return false;
    }

    function update_image($id_user, $url){
        $query = "UPDATE users SET img_account = '$url' WHERE id = $id_user";
        $res = $this->conn->prepare($query);
        $res->execute();
        return $res;
    }

    function get_info($id_user){
        $query = "SELECT login, img_account FROM users WHERE id = $id_user";
        $user = $this->conn->prepare($query);
        $user->execute();
        $row = $user->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // обновить запись пользователя 
    function update(){
     
        // Если в HTML-форме был введен пароль (необходимо обновить пароль) 
        $password_set=!empty($this->password) ? ", password = :password" : "";
     
        // если не введен пароль - не обновлять пароль 
        $query = "UPDATE " . $this->table_name . "
                SET
                    login = :login,
                    email = :email
                    {$password_set}
                WHERE id = :id";
     
        // подготовка запроса 
        $stmt = $this->conn->prepare($query);
     
        // инъекция (очистка) 
        $this->login=htmlspecialchars(strip_tags($this->login));
        $this->email=htmlspecialchars(strip_tags($this->email));
     
        // привязываем значения с HTML формы 
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':email', $this->email);
     
        // метод password_hash () для защиты пароля пользователя в базе данных 
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }
     
        // уникальный идентификатор записи для редактирования 
        $stmt->bindParam(':id', $this->id);
     
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных 
        if($stmt->execute()) {
            return true;
        }
     
        return false;
    }
}
?>