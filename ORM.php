<?php
require 'appconf.php';

class ORM {

    static $conn;
    private $dbconn;
    protected $table;


    static function getInstance(){
        if(self::$conn == null){
            self::$conn = new ORM();
        }
        return self::$conn;
    }
    
    protected function __construct(){
        
        extract($GLOBALS['conf']);
        $this->dbconn = new mysqli($host, $username, $password, $database);
    }
    
    function getConnection(){
        return $this->dbconn;
    }
    
    function setTable($table){
        $this->table = $table;
    }

     function insert($data){
        $query = "insert into $this->table set ";
        foreach ($data as $col => $value) {
            $query .= $col."= '".$value."', ";
        }
        $query[strlen($query)-2]=" ";
        $state = $this->dbconn->query($query);
        if(! $state){
            return $this->dbconn->error;
        }
        
        return $this->dbconn->affected_rows;
        
    }
    function update($data, $conditions){
        $query = "UPDATE $this->table SET ";
        foreach ($data as $col => $value) {
            $query .= $col."= '".$value."', ";           
        }
        $query[strlen($query)-2]=" ";
        $query .= "WHERE ";
        foreach ($conditions as $col => $value) {
            $query .= $col."= '".$value."'AND ";           
        }
        $query = substr($query, 0, -4);;
        $state = $this->dbconn->query($query);
        if(! $state){
            return $this->dbconn->error;
        }
        
        return $this->dbconn->affected_rows;
        
    }
    function select($data = "all", $conditions = "none"){
        $query = "SELECT ";
        if($data != "all"){
            foreach ($data as $col) {
                $query .= " ".$col.", ";           
            }
            $query[strlen($query)-2]=" ";
        }
        else{
            $query .= " * "; 
        }
        $query .= "FROM $this->table ";
        if($conditions != "none"){
            $query .="WHERE ";
            foreach ($conditions as $col => $value) {
                $query .= $col."= '".$value."'AND ";           
            }
            $query = substr($query, 0, -4);
        }
        $state = $this->dbconn->query($query);
        if(! $state){
            return $this->dbconn->error;
        }
        
        return $state;
        
    }
    function select_extra($data = "all", $conditions = "none", $add = ""){
        $query = "SELECT ";
        if($data != "all"){
            foreach ($data as $col) {
                $query .= " ".$col.", ";           
            }
            $query[strlen($query)-2]=" ";
        }
        else{
            $query .= " * "; 
        }
        $query .= "FROM $this->table ";
        if($conditions != "none"){
            $query .="WHERE ";
            foreach ($conditions as $col => $value) {
                $query .= $col." ".$value." AND ";           
            }
            $query = substr($query, 0, -4);
        }
        $query .= $add;
        $state = $this->dbconn->query($query);
        if(! $state){
            return $this->dbconn->error;
        }
        
        return $state;
        
    }
    function delete($conditions){
        $query = "DELETE FROM $this->table WHERE ";
        foreach ($conditions as $col => $value) {
            $query .= $col."= '".$value."'AND ";           
        }
        $query = substr($query, 0, -4);
        $state = $this->dbconn->query($query);
        if(! $state){
            return $this->dbconn->error;
        }
        
        return $this->dbconn->affected_rows;
        
    }
}
?>

