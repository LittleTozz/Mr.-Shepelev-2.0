<?php
class Comment {

    private $conn;

    public $id_comment;
    public $text;
    public $id_music;
    public $id_user;
    public $response;
    public $date;
    
    public function __construct($db){
        $this->conn = $db;
    }

    function get_comments($id_music){
        $query = "SELECT * FROM comments WHERE id_music = $id_music";
        $comments = $this->conn->prepare($query);
        $comments->execute();
        return $comments;
    }

    function insert($text, $id_music, $id_user, $response){
        date_default_timezone_set('Europe/Moscow');
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO comments VALUES (null, '$text', $id_music, $id_user, $response, '$date')";
        if ($response == ''){
            $query = "INSERT INTO comments VALUES (null, '$text', $id_music, $id_user, null, '$date')";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function remove($id_comment){
        $query = "DELETE FROM comments WHERE id_comment = $id_comment";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>