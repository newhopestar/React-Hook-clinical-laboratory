<?php
// 'log' object
class Log{
 
    // database connection and table name
    private $conn;
    private $table_name = "log";
 
    // object properties
    public $id;
    public $user_id;
    public $npi;
    public $action;
    public $current_date_time;
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
	
// create() method will be here
// create new log record
   function create(){
 
    // insert query
    $query = "INSERT INTO " . $this->table_name . "
            SET
                user_id = :user_id,
                npi = :npi,
                action = :action,
                current_date_time = :current_date_time
                ";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    //$this->user_id=htmlspecialchars(strip_tags($this->user_id));
    // $this->npi=htmlspecialchars(strip_tags($this->npi));
    // $this->action=htmlspecialchars(strip_tags($this->action));
    // $this->current_date_time=htmlspecialchars(strip_tags($this->current_date_time));
    
    // bind the values
    $stmt->bindParam(':user_id', $this->user_id);
    $stmt->bindParam(':npi', $this->npi);
    $stmt->bindParam(':action', $this->action);
    $stmt->bindParam(':current_date_time', $this->current_date_time);
 
    // execute the query, also check if query was successful
    if($stmt->execute()){
         return true;
    }
 
		return false;
	}
}
