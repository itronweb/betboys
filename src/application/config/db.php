<?php 

require_once "configDB.php";
require_once "MysqliDb.php";

class DB extends ConfigDB{
	public $cn;
	public $_mysqli;
	public function __construct(){

		// Create connection

		$this->_mysqli = new MysqliDb($this->servername, $this->username, $this->password, $this->dbname);
		
		$this->cn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);	
		// Check connection
		if (!$this->cn) {
			die("Connection failed: " . mysqli_connect_error());
		}

		$this->cn->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'"); 

	}
	
	public function get_query($query){
		$result = $this->cn->query($query);
		$return_select = array();
		
		if( !$result )
			return 0;
		if( $result->num_rows > 0 ){
			while( $row = $result->fetch_assoc()){
				$return_select[] = $row;
				
			}
		}
		else{
			return(0);
		}
		
		return $return_select;
	}
	
	public function get_insert_query($query){
		$result = $this->cn->query($query);
		return $result;
	}
	
	public function select($table_name,$variable,$where_column='1',$where_value='1'){
		$query = "SELECT $variable FROM $table_name WHERE $where_column = '{$where_value}'";
		$result = $this->cn->query($query);
		$return_select = array();
		if( !$result )
			return 0;
		if( $result->num_rows > 0 ){
			while( $row = $result->fetch_assoc()){
				$return_select[] = $row;
				
			}
		}
		else{
			return(0);
		}
		return($return_select);
		
	}
	
	public function multi_select($table_name,$variable,$where_column=array(),$where_value=array(),$order=null,$like=null,$between = null){
		
		if( empty($where_column) || empty($where_value))
			return 0;
		
		$where_query = ' WHERE ';
		foreach ( $where_column as $key=>$value ){
			$where_query .= "$value = '$where_value[$key]' AND ";
		}
		
		$where_query .= '1=1';
		
		$order = !empty($order) ? " ORDER BY $order " : ' ';

		$where_query .= !empty($like) ? " AND $like[0] LIKE '%$like[1]%'" : ' ';
		
		$where_query .= !empty($between) ? " AND $between[0] BETWEEN $between[1] AND $between[2] " : ' ';

		$query = "SELECT $variable FROM $table_name $where_query $order";

		$result = $this->cn->query($query);
		$return_select = array();
		if( !$result )
			return 0;
		if( $result->num_rows > 0 ){
			while( $row = $result->fetch_assoc()){
				$return_select[] = $row;
				
			}
		}
		else{
			return(0);
		}
		return($return_select);
		
	}
	
		
	public function find($table_name,$variable,$where_column,$where_value){
		$query = "SELECT $variable FROM $table_name WHERE $where_column='{$where_value}'";
		
		$result = $this->cn->query($query);
		
		if ($result->num_rows > 0) {
			return($result->fetch_assoc());
			
		} else {
			return(0);
		}

	}
	
//	public function update($table_name,$column,$value,$where_column,$where_value){
//		$query = "UPDATE $table_name SET $column='{$value}' WHERE $where_column='{$where_value}'";
//		$this->cn->query($query);
//	}
	
	public function joinTwoTable($table_name1,$table_name2,$param1,$param2,$where_column,$where_value){
		$query = "SELECT * FROM $table_name1 JOIN $table_name2 ON $param1=$param2 WHERE $where_column='{$where_value}'";
//		var_dump($query);
		$result = $this->cn->query($query);
		$return_select = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				$return_select[] = $row;
			}
			
		} else {
			return(0);
		}
		return($return_select);
	}
	
	public function joinTwoTableMybet($table_name1,$table_name2,$param1,$param2,$where_column,$where_value){
		$query = "SELECT *,$table_name1.created_at as created FROM $table_name1 INNER JOIN $table_name2 ON $param1=$param2 WHERE $where_column='{$where_value}' ORDER BY $table_name1.id DESC";
//		var_dump($query);
		$result = $this->cn->query($query);
		$return_select = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				$return_select[] = $row;
			}
			
		} else {
			return(0);
		}
		return($return_select);
	}
	
	public function joinTwoTableBet($table_name1,$table_name2,$param1,$param2,$where_column,$where_value){
		$query = "SELECT *,$table_name1.created_at as created FROM $table_name1 LEFT JOIN $table_name2 ON $param1=$param2 WHERE $where_column='{$where_value}' AND result_status = '0' ORDER BY $table_name1.id DESC";
//		var_dump($query);
		$result = $this->cn->query($query);
		$return_select = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				$return_select[] = $row;
			}
			
		} else {
			return(0);
		}
		return($return_select);
	}
	
	
	public function insert ( $table_name, $value ){
		
		$a_params = array();
		$column = $this->set_column_name( $value );
		
		$param_type = '';
		$this->set_column_type( $param_type, $value );
		
		$a_params[] = & $param_type;
		
		$key = array_keys($value);
		
		for($i = 0; $i < sizeof($value); $i++) {
		  $a_params[] = & $value[$key[$i]][0];
		}
		
		$query_insert = "INSERT INTO $table_name $column ";

		$stmt = $this->cn->prepare($query_insert);

		call_user_func_array(array($stmt, 'bind_param'), $a_params);

		$stmt->execute();
	}
	
	public function set_column_name ( $value ){
		$name = "( ";
		$i = 0;
		foreach ( $value as $key=>$item ){
			$name .= $key;
			
			if ( $i != sizeof($value)-1){
				$name .= ',';
			}
			$i++;
		}
		$name .= ") ";
		
		$value = $this->set_question_sign( $value );
		
		return " $name VALUES $value ";
	}
	
	public function set_question_sign ( $column_name ){
		$value = " ( ";
		
		if ( is_array($column_name) ){
			for( $i=0; $i< sizeof($column_name); $i++){
				$value .= "?";
				if ( $i+1 < sizeof($column_name))
					$value .= ",";
			}
		}
		else {
			$value .= "?";
		}
		
		$value .= ") ";
		
		return $value;
	}
	
	public function set_column_type ( &$param_type, $value  ){
		
		$key = array_keys($value);
		
		for($i = 0; $i < sizeof($value); $i++) {
		  $param_type .= $value[$key[$i]][1];
		}
	}
	
	public function update ( $table_name, $value , $where ){
		
		$a_params = array();
		$column = $this->set_column_name_update( $value );
		$wheres = $this->set_where_update( $where );
		
		$param_type = '';
		$this->set_column_type_update( $param_type, $value, $where );
		
		$a_params[] = & $param_type;
		
		$key = array_keys($value);
		
		for($i = 0; $i < sizeof($value); $i++) {
		  $a_params[] = & $value[$key[$i]][0];
		}
		
		$keys = array_keys($where);
		for($i = 0; $i < sizeof($where); $i++) {
		  $a_params[] = & $where[$keys[$i]][0];
		}
		
		$query_insert = "UPDATE $table_name SET $column WHERE $wheres ";

		$stmt = $this->cn->prepare($query_insert);

		call_user_func_array(array($stmt, 'bind_param'), $a_params);

		$stmt->execute();
	}
	
	public function set_column_name_update ( $value ){
		$name = " ";
		$i = 0;
		foreach ( $value as $key=>$item ){
			$name .= " $key=? ";
			
			if ( $i != sizeof($value)-1){
				$name .= ',';
			}
			$i++;
		}
		
		return " $name ";
	}
	
	public function set_where_update ( $where ){
		$name = " ";
		$i = 0;
		foreach ( $where as $key=>$item ){
			$name .= " $key=? ";
			
			if ( $i != sizeof($where)-1){
				$name .= ' AND ';
			}
			$i++;
		}
		
		return " $name ";
	}
	
	public function set_column_type_update ( &$param_type, $value, $where  ){
		
		$key = array_keys($value);
		
		for($i = 0; $i < sizeof($value); $i++) {
		  $param_type .= $value[$key[$i]][1];
		}
		$keys = array_keys($where);
		for($i = 0; $i < sizeof($where); $i++) {
		  $param_type .= $where[$keys[$i]][1];
		}
	}
	
	public function get_mix_bet(){
		
		$query = "SELECT id FROM bets WHERE status = 0 AND type != 1";
		
		$result = $this->cn->query($query);
		$return_select = array();
		
		if( !$result )
			return 0;
		if( $result->num_rows > 0 ){
			while( $row = $result->fetch_assoc()){
				$return_select[] = $row;
				
			}
		}
		else{
			return(0);
		}
		
		return $return_select;
		
	}
}

?>