<?php
// 'user' object
class Verify{
 
    // database connection and table name
    private $conn;
    private $table_name = "verify";
 
    // object properties
    public $id;
    public $user_id;
    public $code;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create() method will be here
// create new user record
function create(){
 
    // insert query
    $query = "INSERT INTO " . $this->table_name . "
            SET
                user_id = :user_id,
                code = :code
                ";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
    $this->code=htmlspecialchars(strip_tags($this->code));
    
    // bind the values
    $stmt->bindParam(':user_id', $this->user_id);
    $stmt->bindParam(':code', $this->code);
 
    // execute the query, also check if query was successful
    if($stmt->execute()){
       return $user_id = $this->conn->lastInsertId();
    }
 
    return false;
}


// check if given email exist in the database
function codeExists(){
 
    // query to check if email exists
    $query = "SELECT id, user_id
            FROM " . $this->table_name . "
            WHERE code = ?";
   
    // prepare the query
    $stmt = $this->conn->prepare( $query );
    // sanitize
    $this->code=htmlspecialchars(strip_tags($this->code));
    // bind given email value
    $stmt->bindParam(1, $this->code);
    
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->user_id = $row['user_id'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}


}