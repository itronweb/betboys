<?php session_start();
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);
//require ROOT_PATH . '/config/dbconfig.php';

define('DB_SERVER',"localhost");
define('DB_SERVER_USERNAME',"vip90_sgbs");
define('DB_SERVER_PASSWORD',"Kmcu80!9");
define('DB_DATABASE',"vip90_sgbs");

class DB {
	
	private $servername = DB_SERVER;
	private $username = DB_SERVER_USERNAME;
	private $password = DB_SERVER_PASSWORD;
	private $dbname = DB_DATABASE;
	private $conn;
//	public function getDatabase() {
	    
//	  $site_config["site_title"]; 
	    
//	}
	public function __construct()
	{
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
	}
	
	public function __destruct()
	{
		$this->conn->close();
	}
	
	public function multi_select( $table, $col, $col_name_arr, $col_content_arr)
	{
		if(!is_array($col_name_arr) || ! is_array($col_content_arr)){return "Invalid arguement, array require for arguement 3 and 4.";}
		
		$col_name_len = count($col_name_arr);
		$col_content_len = count($col_content_arr);
		
		if($col_name_len !== $col_content_len){ return "Error on condition."; }
		
		$query = "SELECT ".$col." FROM ". $table . " WHERE ";
		
		for($i = 0; $i < $col_name_len; $i++)
		{
			$query .= $col_name_arr[$i]."='" . $col_content_arr[$i] . "' ";
			if($i !== $col_name_len - 1 )
			{
			    $query .= " AND ";
			}
		}
		
		$query .= "LIMIT 1";
		
		$result = $this->conn->query($query);

		$obj = 0;
		
		if ($result->num_rows > 0) {
			
			$obj = $result->fetch_assoc();
			
		}
		
		return $obj;
		
	}
	
	public function select( $table, $col, $col_name, $col_content)
	{
		
		$query = "SELECT ".$col." FROM ". $table . " WHERE " . $col_name."='" . $col_content . "' LIMIT 1";
		
		$result = $this->conn->query($query);
		
		$obj = 0;
		
		if ($result->num_rows > 0)
		{
			$obj = $result->fetch_assoc();
		}
		
		return $obj;
	}
	
}


?>