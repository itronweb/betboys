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
	Copyright (c) S.C. InterAKT Online SRL
	http://www.interakt.ro/
*/

var drawRelationsAsMoving = false;
var locales = new Array();

locales["Please wait while loading..."] = "Please wait while loading...";
// implemented:
locales["QuB cannot edit queries saved with QuB versions greater than [%s]."] = "Query Builder cannot edit queries saved with Query Builder versions greater than [%s].";
locales["QuB cannot edit queries saved with QuB versions prior to version [%s]."] = "Query Builder cannot edit queries saved with Query Builder versions prior to version [%s].";
locales["Invalid query source!"] = "Invalid query source!\r\nUnable to compile the query source.\r\nError:%s";
locales["Cannot save empty query."] = "Cannot save empty query.";
locales["You must restart QuB to use the imported relations."] = "You must restart Query Builder to use the imported relations.";
locales["Available for PostgreSQL only."] = "Available for PostgreSQL only.";
locales["Saved"] = "Saved";
locales["Deleted"] = "Deleted";
locales["Save current Query?"] = "Save current Query?";
locales["Query modified. Leave page?"] = "The current query has been modified. Do you want to leave this page and lose all the changes?";

locales["Delete current Query?"] = "Delete current Query?";
locales["Delete selected queries?"] = "Delete selected query?";

locales["A query with this name already exists, please choose another name."] = "A query with this name already exists, please choose another name.";
locales["Invalid query name! Please use only alphabetic characters, digits or underscores."] = "Invalid query name! Please use only alphabetic characters, digits or underscores.";

locales["No queries found"] = "No queries found";

locales["Are you sure you want to import relations?"] = "Are you sure you want to import relations?";
locales["Are you sure you want to close QuB?"] = "Are you sure you want to close Query Builder?";
locales["Are you sure you want to remove"] = "Are you sure you want to remove %s from query?";
locales["Not yet available."] = "Not yet available.";
locales["Please select a row."] = "Please select a row.";
locales["No tables were found."] = "No tables were found.";
locales["No tables were selected."] = "No tables were selected.";
locales["All tables are shown."] = "All tables are shown.";
locales["Table"] = "Table";
locales["is unlinked and used."] = "is unlinked and used.";
locales["Circular reference detected."] = "Circular reference detected.";
locales["Please select 2 tables."] = "Please select 2 tables.";
locales["No tables are selected."] = "No tables are selected.";
locales["The selected Link was not found."] = "The selected Link was not found.";
locales["Please select a link."] = "Please select a relation.";
locales["Are you sure you want to delete all relations\nbetween tables "] = "Are you sure you want to delete all relations\nbetween tables ";
locales[" and "] = " and ";
locales["Are you sure you want to delete this link?"] = "Are you sure you want to delete this relation?";
locales["Please use at least one table"] = "<br><dir><b>Please use at least one table!</b></dir>";

locales["Empty alias not allowed."] = "Empty alias not allowed.";
locales["Empty alias not allowed for an aggregated column."] = "Empty alias not allowed for an aggregated column.";

//drag&drop
locales["drag to create a relation"] = "drag to create a relation";
locales["cant link here"] = "can not create link here...";
locales["link here:"] = "create link: "
// not implemented:
locales["Must select 2 tables."] = "Must select 2 tables.";
locales["Open"] = "Open";
locales["New"] = "New";
locales["Save"] = "Save";
locales["Save As"] = "Save As";
locales["Print"] = "Print";
locales["Close"] = "Close";
locales["Add"] = "Add";
locales["Remove"] = "Remove";
locales["Relation"] = "Relation";
locales["Import"] = "Import";
locales["Zoom In"] = "Zoom In";
locales["Zoom Out"] = "Zoom Out";
locales["SQL Query"] = "SQL Query";
locales["Result"] = "Result";
locales["Query Columns"] = "Query Columns";
locales["Add Tables"] = "Add Tables";
locales["Tables to add:"] = "Tables to add:";
locales["OK"] = "OK";
locales["Cancel"] = "Cancel";
locales["View Rows"] = "View Rows";
locales["Add Related Tables"] = "Add Related Tables";
locales["Add Relation"] = "Add Relation";
locales["Edit"] = "Edit";
locales["Unlock Position"] = "Unlock Position";
locales["Table"] = "Table";
locales["Field"] = "Field";
locales["Relation Type"] = "Relation Type";
locales["Restrict"] = "Restrict";
locales["Column Name"] = "Column Name";
locales["Condition Type"] = "Condition Type";
locales["Param Type"] = "Param Type";
locales["Parameter"] = "Parameter";
locales["Test Value"] = "Test Value";
locales["Select"] = "Select";
locales["Functions"] = "Functions";
locales["As"] = "As";
locales["Sort"] = "Sort";
locales["Add Condition"] = "Add Condition";
locales["Add Column"] = "Add Column";
locales["Column"] = "Column";
locales["Insert"] = "Insert";
locales["Show All"] = "Show All";


var HELP_SITE_ROOT = "http://help.adobe.com/en_US/Dreamweaver/9.0_ADDT/help.html";
var help_topics = [];

//Main QuB Help Menu option - 045100_RetrieveDataDB.htm
help_topics["qub"] = "045100_RetrieveDataDB.htm";

//QuB UI - query panel [butonul de help din stanga sus] - 045105_ManageQueries.htm
help_topics["qub.ui.queries"] = "045105_ManageQueries.htm";

//QuB UI - table panel [butonul de help din dreapta jos - la tables] - 045106_WorkDBTables.htm
help_topics["qub.ui.tables"] = "045106_WorkDBTables.htm";

//QuB UI - columns panel [butonul de help din stanga - pe sectiunea Columns] - 045108_WorkTableColumns.htm 
help_topics["qub.ui.sqlcolumns"] = "045108_WorkTableColumns.htm";

//QuB UI - SQL Query/ Results[butonul de help din stanga - pe sectiunea SQL Query / Results] - 045109_ViewGeneratedSQL.htm
help_topics["qub.ui.sqlquery"] = "045109_ViewGeneratedSQL.htm";
help_topics["qub.ui.sqlresults"] = "045110_ViewQueryResults.htm";
 
//QuB secondary UI's [accessibles from the menu options or the different buttons]
 
//QuB Settings - butonul de help de pe interfata Settings trebuie sa deschida: 045102_ConfigureQueryBuilder.htm
help_topics["qub.dlg.settings"] = "045102_ConfigureQueryBuilder.htm";

//QuB New Query user interface - 045105a_CreateQuery.htm
help_topics["qub.dlg.newquery"] = "045105a_CreateQuery.htm";

//QuB Save Query As User Interface - 045105_ManageQueries.htm
help_topics["qub.dlg.saveas"] = "045105_ManageQueries.htm";

//QuB Edit SQL Query Condition- 045108e_DefineQueryConditions.htm
help_topics["qub.dlg.editsqlcondition"] = "045108e_DefineQueryConditions.htm";

//QuB Edit Table Relations - 045107b_EditRelation.htm
help_topics["qub.dlg.editrelation"] = "045107b_EditRelation.htm";

//QuB Edit Table Alias - 045106_WorkDBTables_Edit_Table_Alias.htm
help_topics["qub.dlg.changetablealias"] = "045106_WorkDBTables_Edit_Table_Alias.htm";
