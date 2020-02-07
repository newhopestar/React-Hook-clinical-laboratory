<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "providers";
 
    // object properties
    public $id;
    public $name;
    public $practice;
    public $npi;
    public $email;
    public $password;
    public $active;
    // public $email_verify;
    // public $verify_code;
 
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
                name = :name,
                practice = :practice,
                npi = :npi,
                email = :email,
                password = :password,
                active = :active
                ";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->practice=htmlspecialchars(strip_tags($this->practice));
    $this->npi=htmlspecialchars(strip_tags($this->npi));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->active=htmlspecialchars(strip_tags($this->active));
    
    // bind the values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':practice', $this->practice);
    $stmt->bindParam(':npi', $this->npi);
    $stmt->bindParam(':email', $this->email);
 
    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':active', $this->active);
 
    // execute the query, also check if query was successful
    if($stmt->execute()){
        return $user_id = $this->conn->lastInsertId();
        // return true;
    }
 
    return false;
}



// check if given email exist in the database
function user_details($user_id = ''){
 
    // query to check if email exists
    $query = "SELECT id as user_id, name, practice, npi, active
            FROM " . $this->table_name . "
            WHERE id = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // bind given email value
    $stmt->bindParam(1, $user_id);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        return $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    }
 
    // return false if email does not exist in the database
    return array();
}

 
// emailExists() method will be here
// check if given email exist in the database
function emailExists($user_id = ""){
 
    // query to check if email exists
    $query = "SELECT id, password, active
            FROM " . $this->table_name . "
            WHERE email = ?";
    if ($user_id != "") {
        $query .= " and id != ?";
    }
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // bind given email value
    $stmt->bindParam(1, $this->email);
    
    if ($user_id != "") {
        $stmt->bindParam(2, $user_id);
    }
 
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
        $this->password = $row['password'];
        $this->active = $row['active'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
 
// update_access_token() method will be here
// update_access_token a user record
public function update_access_token($access_token = "",$user_id = ""){
 
    // if no posted password, do not update the password
    $query = "UPDATE " . $this->table_name . " SET access_token = :access_token WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    $this->access_token=htmlspecialchars(strip_tags($access_token));
    // bind the values from the form
    $stmt->bindParam(':access_token', $this->access_token);
 
    // unique ID of record to be edited
    $stmt->bindParam(':id', $user_id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

// update a user record
public function update() {
 
    // if password needs to be updated
    $password_set=!empty($this->password) ? ", password = :password" : "";
 
    // if no posted password, do not update the password
    $query = "UPDATE " . $this->table_name . "
            SET
                name = :name,
                practice = :practice,
                npi = :npi,
                email = :email
                {$password_set}
            WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->practice=htmlspecialchars(strip_tags($this->practice));
    $this->npi=htmlspecialchars(strip_tags($this->npi));
    $this->email=htmlspecialchars(strip_tags($this->email));

    // bind the values from the form
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':practice', $this->practice);
    $stmt->bindParam(':npi', $this->npi);
    $stmt->bindParam(':email', $this->email);
 
    // hash the password before saving to database
    if(!empty($this->password)){
        $this->password=htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    }
    // unique ID of record to be edited
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

// update a user record
public function update_password() {
    // if no posted password, do not update the password
    $query = "UPDATE " . $this->table_name . "
            SET password = :password 
            WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    $this->password=htmlspecialchars(strip_tags($this->password));
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);

    // unique ID of record to be edited
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

// update a user record
public function active_user() {
    $query = "UPDATE " . $this->table_name . "
            SET active = :active 
            WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':active', $this->active);

    // unique ID of record to be edited
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


}