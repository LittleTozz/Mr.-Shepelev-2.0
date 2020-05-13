<?php
class Audition {

    private $conn;

    public $id;
    public $id_music;
    public $id_user;
    public $time;
    public $date;
    
    public function __construct($db){
        $this->conn = $db;
    }

    function get_audition($id_music){
        $query = "SELECT * FROM audition WHERE id_music = $id_music";
        $comments = $this->conn->prepare($query);
        $comments->execute();
        return $comments;
    }

    function insert($id_music, $id_user, $time){
        date_default_timezone_set('Europe/Moscow');
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO audition VALUES (null, $id_music, $id_user, '$time', '$date')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function remove($id_comment){
        $query = "DELETE FROM audition WHERE id_comment = $id_comment";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>