<?php 
class Connection{	  
    public static function connect() {        
        define('host', '127.0.0.1');
        define('dbname', 'sistema');
        define('user', 'root');
        define('password', '');					        
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');			
        try{
            $cnx = new PDO("mysql:host=".host."; dbname=".dbname, user, password, $options);			
            return $cnx;
        }catch (Exception $e){
            die("The connection error: ". $e->getMessage());
        }
    }
}