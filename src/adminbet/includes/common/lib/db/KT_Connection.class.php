<?php
/*
 * ADOBE SYSTEMS INCORPORATED
 * Copyright 2007 Adobe Systems Incorporated
 * All Rights Reserved
 * 
 * NOTICE:  Adobe permits you to use, modify, and distribute this file in accordance with the 
 * terms of the Adobe license agreement accompanying it. If you have received this file from a 
 * source other than Adobe, then your use, modification, or distribution of it requires the prior 
 * written permission of Adobe.
 */

/*
	Copyright (c) InterAKT Online 2000-2006. All rights reserved.
*/

/**
 * The connection class (used on PHP - MySQL Server model)
 */
class KT_Connection {

	/**
	 * The database name
	 * @var string
	 * @access private
	 */
	var $databaseName = '';

	/**
	 * The connection Resource ID
	 * @var object ResourceID
	 * @access private
	 */
	var $connection = null;
	
	/**
	 * Flag. what server model is.
	 * @var string
	 * @access private
	 */
	var $servermodel = "mysql";
	
	/**
	 * for ADODB compatibility
	 * @var string
	 * @access public
	 */
	var $databaseType = "mysql";

	/**
	 * The constructor
	 * Sets the connection and the database name
	 * @param object ResourceID &$connection
	 * @param string $databasename
	 * @access public
	 */
	function KT_Connection(&$connection, $databasename) {
		$this->connection = &$connection;
		$this->databaseName = $databasename;
	}

	/**
	 * Executes a SQL statement
	 * @param string $sql
	 * @return object unknown
	 *         true on success
	 *         response Resource ID if one is returned by the wrapper function
	 * @access public
	 */
	function Execute($sql) {
		if (!mysqli_select_db($this->connection,$this->databaseName)) {
			return false;
		}			
		$response = mysqli_query($this->connection,$sql);
		if (!is_object($response)) {
			return $response;
		} else {
			$recordset = new KT_Recordset($response);
			return $recordset;
		}
	}

	/**
	 * Executes a SQL statement
	 * @param string $sql
	 * @return mysql resource
	 *         true on success
	 *         response MYSQL Resource ID
	 * @access public
	 */
	function MySQL_Execute($sql) {
		if (!mysqli_select_db($this->connection,$this->databaseName)) {
			return false;
		}	
		$response = mysqli_query($this->connection,$sql);
		return $response;
	}

	/**
	 * Gets the error message
	 * @return string
	 * @access public
	 */
	function ErrorMsg() {
		return mysqli_error($this->connection);
	}

	/**
	 * Gets the auto-generated inserted id (if any)
	 * @return object unknown
	 * @access public
	 */
	function Insert_ID($table, $pKeyCol) {
		return mysqli_insert_id($this->connection);
	}
}
?>