<?php 
 include_once 'Define.php';
    class Database{
    
        // specify your own database credentials
        private $_hostname = _HOST_OUTWARD_;
        private $_database =  _DATABASE_OUTWARD_;
        private $_username = _USERNAME_OUTWARD_;
        private $_password = _PASSWORD_OUTWARD_;
        public $_connection;
        private static $_instance; 
    

        public static function getInstance()
        {
            if (!self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
    

        // get the database connection
        public function __construct(){           
          
            try {
                $this->_connection =new PDO("mysql:host=$this->_hostname;dbname=$this->_database", $this->_username, $this->_password); 
           
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
    
        }
        
    }

?>