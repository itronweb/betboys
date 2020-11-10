<?php

	/**
	 * File: Database.php
	 * Author: David@Refoua.me
	 * Version: 0.6.5
	 */
	 
	if ( basename($_SERVER['PHP_SELF']) == basename(__FILE__) ) {
		header('Content-Type: text/plain');
		error_reporting(E_ALL); ini_set('display_errors', 'On');
	}
	
	// Check if all the required extensions are present
	foreach ( ['PDO', 'pdo_mysql'] as $extension ) if( !extension_loaded($extension) ) {
		trigger_error ("The required '$extension' extension, is not enabled.");
		die ("\n");
	}
	
	// TODO: __commentme__
	// TODO: move $db_name after $db_password, also add $PDO_options
	function dbInit($dsn, $db_name = null, $db_username = null, $db_password = null) {
		
		// Remove all whitespace, tabs and newlines
		$dsn = preg_replace( '|\s+|', '', $dsn );
	
		try {
			$db = new PDO($dsn, $db_username, $db_password, [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			]);
			
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			if ( !empty($db_name) ) dbSelect($db, $db_name);
			
			// Make sure the `set names utf8` is executed even if the DB name is not passed as an argument,
			// however it should only be executed on MySQL servers. Executing it on a MSSQL server returns an error.
			else try {$db->exec('set names utf8');} catch(PDOException $nothing) {}
			
		}

		catch( PDOException $e ) {
			$db = null;
			
			$code = $e->getCode();
			echo 'Line ' . $e->getLine() . ': ' . $e->getMessage();
		}
	
		// Return Database handle
		return $db;
	}
	
	// TODO: add try {} catch and handle errors
	// TODO: ($db instanceof PDO) === true
	function dbSelect( $db, $db_name, $create = false ) {
		
		// First sanitize the db name, just in case
		$db_name = sanitizeName($db_name);
		
		// Check if the specified Database exist
		if ( !$create ) $db->exec("USE `$db_name`;");
		
		// Make sure that we have write access and database actually exists
		$db->exec("CREATE DATABASE IF NOT EXISTS `$db_name`; USE `$db_name`;");
		
		// Set the default encoding to UTF-8
		$db->exec('set names utf8');
		
		return $db;
		
	}
	
	/** Deprecated, all of these methods will be replaced by OOP class ASAP. */
	function useHandle( $dbHandle ) {
		
		global $db, $dbLast;
		
		if ( ($dbHandle instanceof PDO) === true ) {
			list($db, $dbLast) = array($dbHandle, $db);
			
			return true;
		}
		
		return false;
		
	}
	
	function getHandle() {
		
		global $db;
		
		if ( ($db instanceof PDO) === true ) {
			return $db;
		}
		
		return null;
		
	}
	
	function formatSQL( $sql ) {
		
		global $db;
		
		// First, trim any useless spaces
		$sql = trim( preg_replace( '|\s+|', ' ', $sql ) );
		
		// Replace any empty selection i.e. INSERT INTO `table_name` ()
		$sql = preg_replace( '|(`\w+`)\s*\(\s*\)|iU', '$1', $sql );
		
		// Remove any empty clause i.e. WHERE()
		$sql = preg_replace( '|\b\w+\b\s*\(\s*\)|iU', '', $sql );
		
		// If the LIMIT amount is set to INF, remove the clause
		$sql = str_replace( 'LIMIT INF', '', $sql);
		
		// Remove additional white-spaces and keep only one semicolon 
		$sql = trim( trim($sql), ';' ) . ';';
		
		// Check for server-specific corrections
		$dbDriver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
		
		// Microsoft SQL Server based queries
		if ( in_array($dbDriver, ['sqlsrv', 'mssql', 'dblib']) ) {
			
			// Change the "`..`" format to "[...]" format
			$sql = preg_replace( '|\`([^\`]+)\`|iU', '[\1]', $sql );
			
			// Change "LIMIT n" to "TOP n" format
			$sql = preg_replace_callback( '@(?:^|;)(?<clause>\w+)\s+(?<parameters>[^\;]+)\s+LIMIT (?<limit>\w+)\;@iU',
				function($section) { return "${section['clause']} TOP ${section['limit']} ${section['parameters']}"; }
			, $sql );
			
			// Remove additional white-spaces and keep only one semicolon 
			$sql = trim( trim($sql), ';' ) . ';';
			
		}
		
		//echo $sql; exit;

		return $sql;
		
	}
	
	function preparePost( &$post, $data, $prefix = '', $opr = '=' ) {
		
		$prefix  = sanitizeName  ( $prefix );
		$data    = sanitizeArray ( $data );
		$isAssoc = count(array_filter(array_keys($data), 'is_string')) > 0;
		$isSeq   = array_keys($data) === range(0, count($data) - 1);
		
		if ( !empty($prefix) ) $prefix .= '_';
		if ( empty($post) ) $post = [];
		
		if ( $isAssoc ) {
			$fields  = array_keys($data);
			$pattern = ( empty($opr) ? ":$prefix*" : "`*` $opr :$prefix*" );
			$values  = array_set( $pattern, array_keys($data) );
			foreach ( $data as $key=>$value ) $post[$prefix.$key] = $value;
		} else
		if ( $isSeq ) {
			$fields  = array();
			$values  = array_fill( 0, count($data), '?' );
			$post    = array_values($data);
		} else {
			die("Database.php: Not supported yet!"); // TODO: for any array like array( 3=>'third row', 5=>'fifth row' )
		}
		
		/*
		// TODO: instead of NULL, use DEFAULT for this
		for ( $i=0; $i<count($data); $i++ )
			if ( $data[$i] === NULL) {
				unset($post[$i]);
				$values[$i] = 'NULL';
			}
		*/
			
		return array($fields, $values);
		
	}
	
	function buildWhere( $filters ) {
		$where = [];
		
		foreach( $filters as $key=>$value ) {
			$opr = sanitizeOpr( preg_match( '@^.+\[([^\[\]]+)]$@iU', trim($key), $matches ) ? array_pop($matches) : '=' );
			$key = sanitizeName( preg_replace( '@\[([^\[\]]+)]@iU', '', $key) );
			$where []= str_replace( '*', $key, "`*` $opr ?" );
		}
		
		return implode(' AND ', $where);
	}
	
	//die( buildWhere( [ 'name[NOT]'=>NULL ] ) );
	
	function dbExec( $sql ) {
		GLOBAL $db;
		
		if ( ($db instanceof PDO) === true ) {
			$count   = $db->exec( formatSQL($sql) );
			return $count;
		}
	}
	
	function dbQuery( $sql ) {
		GLOBAL $db;
		
		if ( ($db instanceof PDO) === true ) {
			$result  = $db->query( formatSQL($sql) );
			return $result;
		}
	}
	
	function dbMake( $table, $columns ) {
		GLOBAL $db;
		
		$table   = sanitizeName  ( $table );
		$columns = sanitizeArray ( $columns );
		$columns = array_map('sanitizeType', $columns);
		
		if ( ($db instanceof PDO) === true ) {
			$columns = implode( ', ', array_use('`?` *', $columns) );
			$sql     = ("CREATE TABLE IF NOT EXISTS `$table` ($columns)");
			$count   = $db->exec( formatSQL($sql) );
			return ($count === 0);
		}
		
	}
	
	function dbDestroy( $table ) {
		GLOBAL $db;
		
		$table   = sanitizeName  ( $table );
		
		if ( ($db instanceof PDO) === true ) {
			$sql     = ("DROP TABLE IF EXISTS `$table`");
			$count   = $db->exec( formatSQL($sql) );
			return ($count === 0);
		}
		
	}
	
	// TODO: $limit = implode( ', ', [$offset, $limit] );
	// Examples: 
	// SELECT * FROM Orders LIMIT 5 # Retrieve first 5 rows
	// SELECT * FROM Orders LIMIT 10 OFFSET 15
	// SELECT * FROM Orders LIMIT 15, 10 # Retrieve rows 16-25
	// SELECT * FROM Orders LIMIT 5,10;  # Retrieve rows 6-15
	// LIMIT row_count is equivalent to LIMIT 0, row_count.
	
	// TODO: COUNT(*)
	// https://stackoverflow.com/a/1893431/1454514
	// function dbCount( $table, $filters = [] ) {
	// 		return $count;
	// }
	
	// TODO: ORDER BY
	// SELECT column_name FROM Orders WHERE condition ORDER BY col1 ASC, col2 DESC
	// SELECT * FROM CUSTOMERS ORDER BY NAME, SALARY;
	// SELECT * FROM CUSTOMERS ORDER BY NAME DESC;
	// SELECT * FROM Orders WHERE OrderDate >= '1980-01-01' ORDER BY OrderDate
	
	// For pagination, read example: http://www.xarg.org/2011/10/optimized-pagination-using-mysql/
	
	function dbRead( $table, $filters = [], $limit = INF ) {
		GLOBAL $db;
		
		$limit   = sanitizeInt   ( $limit );
		$table   = sanitizeName  ( $table );
		//$filters = sanitizeArray ( $filters );
		
		if ( ($db instanceof PDO) === true ) {
			$fields  = '*'; // TODO: For now, everything. To be changed later.
			//$where   = implode(' AND ', array_set("`*` = ?", array_keys($filters)));
			$where   = buildWhere( $filters );
			$sql     = ("SELECT $fields FROM `$table` WHERE ($where) LIMIT $limit");
			$stmt    = $db->prepare( formatSQL($sql) );
			$success = $stmt->execute( array_values($filters) );
			$result  = $stmt->fetchAll( PDO::FETCH_ASSOC );
			$count   = $stmt->rowCount();
			return $result;
		}
		
	}

	function dbReadNex( $table, $filters = [], $limit = INF ) {
		GLOBAL $db_nex;
		
		$limit   = sanitizeInt   ( $limit );
		$table   = sanitizeName  ( $table );
		//$filters = sanitizeArray ( $filters );
		
		if ( ($db_nex instanceof PDO) === true ) {
			$fields  = '*'; // TODO: For now, everything. To be changed later.
			//$where   = implode(' AND ', array_set("`*` = ?", array_keys($filters)));
			$where   = buildWhere( $filters );
			$sql     = ("SELECT $fields FROM `$table` WHERE ($where) LIMIT $limit");
			$stmt    = $db_nex->prepare( formatSQL($sql) );
			$success = $stmt->execute( array_values($filters) );
			$result  = $stmt->fetchAll( PDO::FETCH_ASSOC );
			$count   = $stmt->rowCount();
			return $result;
		}
		
	}
	
	function dbCount( $table, $filters = [], $limit = INF ) {
		GLOBAL $db;
		
		$limit   = sanitizeInt   ( $limit );
		$table   = sanitizeName  ( $table );
		//$filters = sanitizeArray ( $filters );
		
		if ( ($db instanceof PDO) === true ) {
			$fields  = '*'; // TODO: For now, everything. To be changed later.
			//$where   = implode(' AND ', array_set("`*` = ?", array_keys($filters)));
			$where   = buildWhere( $filters );
			$sql     = ("SELECT COUNT($fields) FROM `$table` WHERE ($where) LIMIT $limit");
			$stmt    = $db->prepare( formatSQL($sql) );
			$success = $stmt->execute( array_values($filters) );
			//$result  = $stmt->fetchAll( PDO::FETCH_ASSOC );
			$column  = $stmt->fetchColumn();
			$count   = $stmt->rowCount();
			return $column;
		}
		
	}
	
	function dbPrepare( $table, $filters = [], $limit = INF ) {
		GLOBAL $db;
		
		$limit   = sanitizeInt   ( $limit );
		$table   = sanitizeName  ( $table );
		//$filters = sanitizeArray ( $filters );
		
		if ( ($db instanceof PDO) === true ) {
			$fields  = '*'; // TODO: For now, everything. To be changed later.
			//$where   = implode(' AND ', array_set("`*` = ?", array_keys($filters)));
			$where   = buildWhere( $filters );
			$sql     = ("SELECT $fields FROM `$table` WHERE ($where) LIMIT $limit");
			$stmt    = $db->prepare( formatSQL($sql) );
			$success = $stmt->execute( array_values($filters) );
			$count   = $stmt->rowCount();
			return $stmt;
		}
		
	}
	
	function dbFetch( $stmt ) {
		GLOBAL $db;
		
		if ( ($db instanceof PDO) === true ) {
			$result  = $stmt->fetch( PDO::FETCH_ASSOC );
			return $result;
		}
		
	}
	
	function dbGetRow( $table, $filters = [] ) {
		GLOBAL $db;
		
		$rows = dbRead( $table, $filters, 1 );
		return array_pop($rows);
		
	}
	
	function dbAdd( $table, $data ) {
		GLOBAL $db;
		
		$table   = sanitizeName  ( $table );
		$data    = sanitizeArray ( $data );

		if ( ($db instanceof PDO) === true ) {
			list($fields, $values) = preparePost( $post, $data, 'insert', false );
			$fields  = implode(', ', $fields );
			$values  = implode(', ', $values );
			$sql     = ("INSERT INTO `$table` ($fields) VALUES ($values)");
			$stmt    = $db->prepare( formatSQL($sql) );
			$success = $stmt->execute( $post );
			$count   = $stmt->rowCount();
			$id      = $db->lastInsertId();
			return $success && ($count === 1);
		}
	}
	
	// NOTE: $count returns how many updated with new data in MySQL, and how many totally affected in MSSQL
	
	function dbWrite( $table, $filters = [], $data ) {
		GLOBAL $db;
		
		$table   = sanitizeName  ( $table );
		$filters = sanitizeArray ( $filters );
		$data    = sanitizeArray ( $data );
		
		if ( ($db instanceof PDO) === true ) {
			$clause  = implode(', ', preparePost( $post, $data, 'set' )[1] );
			$where   = implode(' AND ', preparePost( $post, $filters, 'where' )[1] );
			$sql     = ("UPDATE `$table` SET $clause WHERE ($where)");
			$stmt    = $db->prepare( formatSQL($sql) );
			$success = $stmt->execute( $post );
			$count   = $stmt->rowCount();
	
			return ($success ? $count : FALSE);
		}
		
	}
	
	function dbRemove( $table, $filters = [] ) {
		GLOBAL $db;
		
		$table   = sanitizeName  ( $table );
		$filters = sanitizeArray ( $filters );
		
		if ( ($db instanceof PDO) === true ) {
			$where   = implode(' AND ', array_set("`*` = ?", array_keys($filters)));
			$sql     = ("DELETE FROM `$table` WHERE ($where)");
			$stmt    = $db->prepare( formatSQL($sql) );
			$success = $stmt->execute( array_values($filters) );
			$count   = $stmt->rowCount();
			
			return $success;
		}
		
	}

	// if ( ($db instanceof PDO) === true ) { ... }

	function dbTransaction() {
		GLOBAL $db;

		if ( !$db->inTransaction() )
			return $db->beginTransaction();

		else return false;
	}

	function dbCommit() {
		GLOBAL $db;

		if ( $db->inTransaction() )
			return $db->commit();

		else return false;
	}

	function dbRollback() {
		GLOBAL $db;

		if ( $db->inTransaction() )
			return $db->rollBack();

		else return false;
	}
	
	function array_build( $glue, $array ) {
		$output = [];
		foreach ( $array as $key=>$value ) $output []= implode( $glue, array($key, $value) );
		return $output;
	}
	
	function array_use( $pattern, $array ) {
		$output = []; foreach ( $array as $key=>$value ) $output [] = 
		str_replace('?', $key, str_replace('*', $value, $pattern));
		return $output;
	}
	
	/*
	function array_set( $replacement, $array ) {
		$output = []; foreach ( $array as $key=>$value )
		$output [    str_replace('@', $key, $replacement) ]=
			         str_replace('*', $value, $replacement);
		return $output;
	}
	*/
	
	function array_set( $replacement, $array ) {
		return array_map(function($key) use(&$replacement) {
			return str_replace('*', $key, $replacement);
		}, $array);
	}
	
	/*
	function array_set( $replacement, $array ) {
		$output = ( preg_match('|[\@\*]|', $replacement) ) ?
		function( $output = [] ) use(&$replacement, &$array) {
			foreach ( $array as $key=>$value ) $output [str_replace('@', $key, $replacement)]= str_replace('*', $value, $replacement);
			return $output;
		} :
		array_map(function($key) use(&$replacement) {
			return str_replace('*', $key, $replacement);
		}, $array);
		return $output;
	}
	
	function array_remap( $replacement, $array ) {
		$output = [];
		foreach ( $array as $key=>$value ) $output [str_replace('*', $key, $replacement)]= ($value);
		return $output;
	}
	*/
	
	function sanitizeInt( $input, $intOnly = false ) {
		
		// Return negative and positive Infinity as-is
		if ( abs($input) === INF ) return $input;
		
		// Remove any kind of whitespace, and/or comma digit separators
		$input = preg_replace( '/[\s|\,]+/', '', (string) $input );
		
		// Remove any non-digit characters
		$input = (float) preg_replace( '|[^\d\-\.e]|iU', '', $input );
		
		// If float isn't accepted, round the number
		if ( $intOnly ) $input = (int) round($input); else
		if ( $input == (int)$input ) $input = intval($input);
		
		return $input;
	}
	
	function sanitizeOpr( $input ) {
		
		// Trim all whitespace characters
		$input = trim($input);
		
		// Remove all invalid characters
		// TODO: complete this
		//$input = preg_replace( '|[^\w\s\[\]]+|', '', $input );
		
		// Truncate to 64 characters
		$input = substr( $input, 0, 64 );
		
		return $input;
	}
	
	function sanitizeName( $input ) {
		
		$output = trim($input);
		
		// Changes any whitespace to underscore characters
		$output = preg_replace( '|\s+|', '_', $output );
		
		// Remove all non-alphanumeric and underscore characters
		$output = preg_replace( '|[^\w\-]+|', '', $output );
		
		// Check if the value should be an integer
		if ( !preg_match( '|^[\d\-\.\+\ \,]+$|', $output ) )

		// Remove numbers from the beginning of the variable
		$output = preg_replace( '|^\d+|', '', $output );
		
		// Otherwise, the value should be an integer
		else $output = sanitizeInt( $output );
		
		// Truncate to 64 characters
		$output = substr( $output, 0, 64 );
		
		return $output;
	}
	
	function sanitizeType( $input ) {
		
		// Remove all invalid characters
		$input = preg_replace( '|[^\w\s\(\)]+|', '', $input );
		
		// Remove excess white-spaces
		$input = preg_replace( '|[\s]+|', ' ', $input );
		
		return trim( $input );
	}
	
	function sanitizeArray( $input ) {
		$output = [];
		foreach ( (array) $input as $name=>$value ) $output [ is_int($name) ? sanitizeInt($name) : sanitizeName($name) ] = is_null($value) ? NULL : (string) $value; // TODO: if ( !( is_string($value) || is_int($value) || is_null($value) ) ) trigger_error("Invalid type");
		return $output;
	}
	
	function sanitizeOutput( $input ) {
		$input = htmlentities( $input );
		return $input;
	}
	
	/* TODO: remove this
	//$clause  = implode(', ', array_build(' = ', array_combine( array_set('`*`', $fields), array_set('*', $values) )));
	function processFilters( $arr ) {
		if ( count(array_filter(array_keys($arr), function($val) {return is_int($val);})) == count($arr) ) $raw = $arr;
		else { $raw = []; foreach ($arr as $key=>$val) { $raw []= $key; $raw []= $val; } }
		return $raw;
	}
	*/
	