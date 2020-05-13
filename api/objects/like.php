<?php
class Like {

    private $conn;

    public $id_like;
    public $id_music;
    public $id_user;
    
    public function __construct($db){
        $this->conn = $db;
    }

    function get_state_like($id_music, $id_user){
        $query = "SELECT id_like FROM likes WHERE id_music = $id_music and id_user = $id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_like'];
    }

    function insert($id_music, $id_user){
        $query = "INSERT INTO likes VALUES (null, $id_music, $id_user)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function remove($id_like){
        $query = "DELETE FROM likes WHERE id_like = $id_like";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>