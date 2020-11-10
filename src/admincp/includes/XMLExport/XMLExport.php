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

$KT_XMLExport_uploadErrorMsg = '<strong>XMLExport Error.</strong><br/>File not found: %s. Please upload the includes/ folder to the testing server.';
$KT_XMLExport_uploadFileList = array('XMLExport.class.php');

for ($KT_XMLExport_i=0;$KT_XMLExport_i<sizeof($KT_XMLExport_uploadFileList);$KT_XMLExport_i++) {
    $KT_XMLExport_uploadFileName = dirname(realpath(__FILE__)). '/' . $KT_XMLExport_uploadFileList[$KT_XMLExport_i];
    if (file_exists($KT_XMLExport_uploadFileName)) {
	require_once($KT_XMLExport_uploadFileName);
    } else {
	die(sprintf($KT_XMLExport_uploadErrorMsg,$KT_XMLExport_uploadFileList[$KT_XMLExport_i]));
    }
}
?>