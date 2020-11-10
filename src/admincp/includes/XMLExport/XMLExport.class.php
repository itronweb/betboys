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
 * This is the class responsable for generating and exporting the XML file.
 */
class XMLExport {
  /**
   * Internal array for storing the column that will be exported.
   */
  var $columns;

  /**
   * The recordset from where the data will be exported.
   */
  var $recordset;

  /**
   * The maximum number of records to be exported. It can be either the string 'ALL' or the actual number of records.
   */
  var $maxRecords;

  /**
   * The encoding in which the data is stored in the database.
   */
  var $dbEncoding;

  /**
   * The encoding which will be set for the exported XML file.
   */
  var $xmlEncoding;

  /**
   * The XML format. It can export the fields as either NODES or ATTRIBUTES.
   */
  var $xmlFormat;

  /**
   * The name of the root element in the exported XML
   */
  var $rootNodeName;

  /**
   * The name of the row element in the exported XML
   */
  var $rowNodeName;

  /**
   * Class constructor. Member variables are initialized to their default values.
   */
  function XMLExport() {
    $this->maxRecords = 'ALL';
    $this->xmlFormat = 'NODES';
    $this->columns = array();
    $this->rootNodeName = 'export';
    $this->rowNodeName = 'row';
  }

  /**
   * This is the setter for the recordset member variable.
   *
   * @param recordset The recordset from where to retrieve the data for the export.
   */
  function setRecordset(&$recordset) {
    $this->recordset = $recordset;
  }

  /**
   * This is the setter for the maxRecords member variable specifying how may records should be exported.
   *
   * @param maxRecords The string 'ALL' or the number of records.
   */  
  function setMaxRecords($maxRecords) {
    $this->maxRecords = trim($maxRecords);
  }

  /**
   * This is the setter for the dbEncoding member variable.
   *
   * @param dbEncoding The encoding in which the data is stored in the database.
   */  
  function setDBEncoding($dbEncoding) {
    $this->dbEncoding = trim($dbEncoding);
  }

  /**
   * This is the setter for the xmlEncoding member variable.
   *
   * @param xmlEncoding The encoding that will be set for the XML.
   */  
  function setXMLEncoding($xmlEncoding) {
    $this->xmlEncoding = trim($xmlEncoding);
  }

  /**
   * This is the setter for the xmlFormat member variable.
   *
   * @param xmlFormat The format in which the XML file should export the data: "NODES" or "ATTRIBUTES".
   */  
  function setXMLFormat($xmlFormat) {
    $this->xmlFormat = strtoupper(trim($xmlFormat));
    if ($this->xmlFormat != 'ATTRIBUTES') {
      $this->xmlFormat == 'NODES';
    }
  }

  /**
   * This is the setter for the rootNodeName member variable.
   *
   * @param rootNodeName The name of the root element in the resulting XML.
   */  
  function setRootNode($rootNodeName) {
    $this->rootNodeName = trim($rootNodeName);
  }

  /**
   * This is the setter for the rowNodeName member variable.
   *
   * @param rowNodeName The name of the row element in the resulting XML.
   */  
  function setRowNode($rowNodeName) {
    $this->rowNodeName = trim($rowNodeName);
  }

  /**
   * This method adds a column to the member variable columns which holds the columns that the XML file will have.
   *
   * @param column The name of the column as it appears in the recordset.
   * @param label  The label that will be used in the exported XML file to identify this field.
   */
  function addColumn($column, $label = '') {
    $this->columns[trim($column)] = array('label' => trim($label));
  }

  /**
   * This method is the one that generates and exports the XML file.
   */
  function Execute() {
    if ( !isset($this->recordset) || !is_object($this->recordset) ) {
      die('<strong>XMLExport Error.</strong><br/>Passed argument is not a valid recordset.');
      return;
    }

    if (count($this->columns) < 1) {
      die('<strong>XMLExport Error.</strong><br/>No columns defined!');
      return;
    }
		
    $document = '';
    $document .= '<'.'?xml version="1.0" encoding="' . $this->xmlEncoding . '"?'.'>';
    $document .= "\r\n";
    $document .= '<' . $this->rootNodeName . '>';
    $document .= "\r\n";

    $numRow = 1;
    if(mysqli_num_rows($this->recordset) > 0) {
      mysqli_data_seek($this->recordset, 0);
    }
    while($row_recordset = mysqli_fetch_assoc($this->recordset)) {
      if ($this->maxRecords != 'ALL' && $this->maxRecords < $numRow) {
        break;
      }
	    
      $row = "\t";
      $row .= '<' . $this->rowNodeName;
      if ($this->xmlFormat != 'ATTRIBUTES') {
        $row .= '>' . "\r\n";
      }
	    
      foreach ($this->columns as $column => $details) {
        $colName = $column;
        if ($details['label'] != '') {
          $colName = $details['label'];
        }
        $value = $row_recordset[$column];
        if ($this->xmlEncoding != $this->dbEncoding) {
          if (!function_exists('mb_convert_encoding')) {
            die('<strong>XMLExport Error.</strong><br/>Could not perform characters conversion. Function <strong>mb_convert_encoding</strong> is not available! Please enable the <strong>mbstring</strong> extension!');
          }
          $value = mb_convert_encoding($value, $this->xmlEncoding, $this->dbEncoding);
        }
        $value = str_replace(array('&', '>', '<', '"'), array('&amp;', '&gt;', '&lt;', '&quot;'), $value);

        if ($this->xmlFormat == 'ATTRIBUTES') {
          $row .= ' ' . $colName . '="';
          $row .= $value;
          $row .= '"';
        } else {
          $row .= "\t\t";
          $row .= '<' . $colName . '>';
          $row .= $value;
          $row .= '</' . $colName . '>';
          $row .= "\r\n";
        }
      }
	    
      if ($this->xmlFormat == 'ATTRIBUTES') {
        $row .= ' />';
      } else {
        $row .= "\t" . '</' . $this->rowNodeName . '>';
      }
	    
      $document .= $row;
      $document .= "\r\n";
      $numRow++;
    }
	
    $document .= '</' . $this->rootNodeName . '>';
    $size = strlen($document);
    $this->sendHeaders($size);
    echo $document;
    exit;
  }

  /**
   * This method is used to send the appropriate headers.
   *
   * @param size The size of the XML being exported.
   */
  function sendHeaders($size) {
    if (headers_sent()) {
      die('<strong>XMLExport Error.</strong><br/>Headers already sent! The XML cannot be exported');
    }

    header('Content-type: text/xml; charset=' . $this->xmlEncoding);
    header('Pragma: public');
    header('Cache-control: private');
    header('Expires: -1');
    header('Content-Length: ' . $size);
  }
}

?>