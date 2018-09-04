<?php
class Database{
    const DB_HOSTNAME = '192.168.16.87';
    const DB_USERNAME = 'ramya';
    const DB_PASSWORD = 'ramya!@#';
    const DB_NAME = 'Transaction';
    protected $_db_connect;
    protected $_sql;
    protected $_result;
    protected $_row;
    protected $data;

    function db_connect()
    {
        $this->_db_connect = mysqli_connect(self::DB_HOSTNAME,self::DB_USERNAME,self::DB_PASSWORD,self::DB_NAME) or die(mysql_error());
       // print_r($this->_db_connect);
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];

      $this->_sql = sprintf("SELECT created_date, txn_count FROM sorted where created_date between 
    '$startDate' and '$endDate'");
       // print_r($this->_sql);
    
    
        $this->_result =mysqli_query($this->_db_connect,$this->_sql);
       // print_r($this->_result);
    
       $data = array();
       foreach($this->_result as $_row)  
       {
        $data[] = $_row;
    
        }
      echo json_encode($data);
               
       }
     }     
$database = new Database();
$database->db_connect();
?>