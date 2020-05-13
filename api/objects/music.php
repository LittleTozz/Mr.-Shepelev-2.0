<?php
class Music {

    // подключение к базе данных и таблице 'products' 
    private $conn;
    private $table_name = "music";

    // свойства объекта 
    public $id_music;
    public $name;
    public $id_category;
    public $duration;
    public $genre;
    public $path;
    public $date;

    // конструктор для соединения с базой данных 
    public function __construct($db){
        $this->conn = $db;
    }

    // метод read() - получение треков
    function read_track(){
        $query = "SELECT * FROM music WHERE id_category = 1 ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    function read_remix(){
        $query = "SELECT * FROM music WHERE id_category = 2 ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    function read_all(){
        $query = "SELECT * FROM music ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function read_one_music($id_music){
        $query = "SELECT * FROM music WHERE id_music = $id_music";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function count($category){
        $query = "SELECT COUNT(*) as total_rows FROM music";

        if ($category != 0)
            $query = "SELECT COUNT(*) as total_rows FROM music WHERE id_category = $category";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }
    
    public function readPaging($from_record_num, $records_per_page, $category){
        $query = "SELECT * FROM music ORDER BY date DESC LIMIT ?, ?";
    
        // выборка 
        if ($category != 0)
            $query = "SELECT * FROM music WHERE id_category = $category ORDER BY date DESC LIMIT ?, ?";
    
        // подготовка запроса 
        $stmt = $this->conn->prepare( $query );
    
        // свяжем значения переменных 
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // выполняем запрос 
        $stmt->execute();
    
        // вернём значения из базы данных 
        return $stmt;
    }
}
?>