<?php

defined('BASE_URL')        OR define('BASE_URL', "http://cldp.canelabs.com/api"); 
defined('SITE_EMAIL')      OR define('SITE_EMAIL', "noreply@cldp.canelabs.com"); 
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "cldp_dev";
    private $username = "cldpdbuser";
    private $password = "dbQe42&7";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>