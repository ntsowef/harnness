<?php
require_once 'config2.php';

/**
 * Description of class
 *
 * @author Frans
 */
class Database {

    
    private  $_connection;
    private static $_instance;
    
   /* public static function getInstance(){
        if(self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    } */
    
    public function __construct() {
        $this ->_connection  = new mysqli('localhost', 'root', '', 'sms_data');
        
        
        if(mysqli_connect_error()){
            trigger_error("Failed to connect to mysql". mysqli_connect_error(), E_USER_ERROR);
        }
    }
    
    public function getConnection(){
        
        return $this->_connection;
    }
    
    public function closeConnection(){
        mysqli_close($this->_connection);   
    }
}
