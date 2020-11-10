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

var qubWindow = null;
var debug = false;

if (window.qs) {
	qubWindow = window;
} else {
	if (window.parent && window.parent.qs) {
		qubWindow = window.parent;
	} else {
		if (window.opener && window.opener.qs) {
			qubWindow = window.opener;
		} else {
			if (window.opener && window.opener.parent && window.opener.parent.qs) {
				qubWindow = window.opener.parent;
			}
		}
	}
}

postString = qubWindow.getPostString();
serverExt = qubWindow.getServerExtension();
Password = qubWindow.getPassword();
//var rcount = 0;
//var sposts = '';
function xmlRequest(opCode) {
	var xmlhttp;
	var postString1 = postString + "&Timeout=10000&opCode=" + opCode;
  try {
    xmlhttp = XmlHttp.create();
  } catch (e) {
		xmlhttp=false
  }
	if (xmlhttp) {
		try{
			//rcount++;
			//sposts += postString1 +"\r\n";
			xmlhttp.open("POST", "../../_mmServerScripts/MMHTTPDB." + serverExt, false);
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xmlhttp.send(postString1);
		} catch(e) {
			alert("Error xmlRequest:\r\nURL:../../_mmServerScripts/MMHTTPDB." + serverExt + "\r\n");
			alert("POST:"+postString1);
		}
		var doc = XmlDocument.create();
		doc.async = false;

		tmp = xmlhttp.responseText;
		//alert(opCode+"\r\n\r\n"+tmp);
		if (tmp.indexOf("<RESULTSET>") != -1) {
			tmp = "<RESULTSET>" + tmp.split("<RESULTSET>")[1];
			tmp = tmp.split("</RESULTSET>")[0] + "</RESULTSET>";
		} else if (tmp.indexOf("<ERRORS>") != -1) {
			tmp = "<ERRORS>" + tmp.split("<ERRORS>")[1];
			tmp = tmp.split("</ERRORS>")[0] + "</ERRORS>";
		}
		if (!doc.loadXML(tmp)) {
			tmp = tmp.replace(/<RESULTSET/g, '<TABLE cellspacing=0 border=1 cellspacing=0  id="sql_result_table"');
			tmp = tmp.replace(/RESULTSET/g, 'TABLE');
			tmp = tmp.replace(/FIELDS/g, 'TR');
			tmp = tmp.replace(/FIELD/g, 'TH');
			tmp = tmp.replace(/ROWS/g, 'rows');
			tmp = tmp.replace(/ROW/g, 'TR');
			tmp = tmp.replace(/VALUE/g, 'TD');
			return tmp;
		}
		return doc;
	}
	return null;
}

function getTablesCfm() {
	var ret = new Array();
	try {
		var arr = getPostCfm('TABLEINFO');
		var i;
		for (i=0;i<arr.length;i++) {
			switch(arr[i][3]) {
				case 'TABLE':
				case 'VIEW':
					ret[ret.length] = arr[i][2];
					break;
			}
		}
	} catch(e) {
		alert(e);
	}
	window.status = 'Quering Server: DONE';
	return ret;
}
	
function getTables() {
	window.status = 'Quering Server ...';
	if (serverExt == 'cfm') {
		return getTablesCfm();
	}
	var ret = new Array();
	var doc = null;
	doc = xmlRequest('GetTables');
	if (doc && doc.documentElement.tagName == 'RESULTSET') {
		var rows = doc.getElementsByTagName('FIELD');
		var fields = new Array();
		if (rows.length > 0) {
			for (var i=0;i<rows.length;i++) {
				fields[rows[i].text]=i+1;
			}
		}

		var rows = doc.getElementsByTagName('ROW');
		for (var i=0;i<rows.length;i++) {
			ret[ret.length] = getData(fields, 'TABLE_NAME', rows, i);	
		}
	}
	window.status = 'Quering Server: DONE';
	return ret;
}

/*
0	
1	
2	table name
3	column name
4	column type number
5	column type string
6	column length
7	column max length?
8	0 ??
9	10 ??
10	0 ??

*/
function getTableInfoCfm(tName) {
	var ret = new Array();
	try {
		var arr = getPostCfm('COLUMNINFO', tName);
		var i;


		for (i=0;i<arr.length;i++) {
			/*	//
			var s = "";
			for(var j=0; j<arr[i].length; j++ ) {
				s += j+"\t"+arr[i][j] +"\r\n";
			}
			alert(s);
			//	*/
			var colName = arr[i][3];
			ret[colName] = new Array();
			ret[colName]['name'] = colName;
			ret[colName]['data_type'] = arr[i][5];
			ret[colName]['column_length'] = arr[i][6];
			//ret[colName]['numeric'] = doc.fields[i].numeric?doc.fields[i].numeric:'';
			if (parseInt(ret[colName]['data_type']) == ret[colName]['data_type']) {
				ret[colName]['data_type'] = metaType(ret[colName]['data_type']);
			}
			//dont know that on CF
			ret[colName]['primary_key'] = 0;			
		}
		ret['length'] = arr.length;
	} catch(e) {
		alert(e);
	}
	window.status = 'Quering Server: DONE';
	return ret;
}
function pn(o) {
	var s = "";
	for (var fn in o) {
		s += fn +"="+o[fn]+"\r\n";
	}
	alert(s);
}
function getTableInfo(tName) {
	window.status = 'Quering Server ...';

	if (serverExt == 'cfm') {
		return getTableInfoCfm(tName);
	}
	var ret = [];
	var doc = null;
	doc = xmlRequest('GetColsOfTable&TableName=' + escape(tName));

	if (doc && doc.documentElement.tagName == 'RESULTSET') {
		var rows = doc.getElementsByTagName('FIELD');
		var fields = new Array();
		if (rows.length > 0) {
			for (var i=0;i<rows.length;i++) {
				fields[rows[i].text]=i+1;
			}
		}

		rows = doc.getElementsByTagName('ROW');

		if (rows.length > 0) {
			for (var i=0;i<rows.length;i++) {	
				var colName = getData(fields, 'COLUMN_NAME', rows, i);

				ret[colName] = new Array();
				ret[colName]['name'] = colName;
				ret[colName]['data_type'] = getData(fields, 'DATA_TYPE', rows, i); 
				//alert(ret[colName]['data_type'])
				ret[colName]['column_length'] = getData(fields, 'COLUMN_LENGTH', rows, i); 

				if (ret[colName]['column_length']=='') {
					ret[colName]['column_length'] = getData(fields, 'COLUMN_SIZE', rows, i);
				}
				if (ret[colName]['column_length']=='null') {
					ret[colName]['column_length'] = '';
				}
				if (ret[colName]['column_length']=='') {
					ret[colName]['column_length'] = getData(fields, 'NUMERIC_PRECISION', rows, i);
				}
				if (ret[colName]['column_length']=='null') {
					ret[colName]['column_length'] = '';
				} else {
					var numeric_precision = getData(fields, 'NUMERIC_SCALE', rows, i)
					if (numeric_precision!='null' && numeric_precision!=='') {
						ret[colName]['column_length'] += ', ' + numeric_precision;
					}
				}
				if (ret[colName]['column_length']=='') {
					ret[colName]['column_length'] = getData(fields, 'CHARACTER_MAXIMUM_LENGTH', rows, i);
				}
				if (ret[colName]['column_length']=='null') {
					ret[colName]['column_length'] = '';
				}
				if (ret[colName]['column_length']=='') {
					ret[colName]['column_length'] = getData(fields, 'DATETIME_PRECISION', rows, i);
				}
				if (ret[colName]['column_length']=='null') {
					ret[colName]['column_length'] = '';
				}

				//alert(ret[colName]['column_length']);
				//alert(ret[colName]['data_type']+"\r\n"+ret[colName]['column_length']);
				if (parseInt(ret[colName]['data_type']) == ret[colName]['data_type']) {
					//ret[colName]['data_type'] = ret[colName]['data_type'] + ":"+metaType2(ret[colName]['data_type']) +":"+metaType(metaType2(ret[colName]['data_type']));
					ret[colName]['data_type'] = metaType(ret[colName]['data_type']);
				} else {
					//ret[colName]['data_type'] = ret[colName]['data_type'] + ":"+metaType2(ret[colName]['data_type']) +":"+metaType(metaType2(ret[colName]['data_type']));
					ret[colName]['data_type'] = metaType(metaType2(ret[colName]['data_type']));
				}
				
				//GetColsOfTable does not return this information
				ret[colName]['primary_key'] = 0;			
			}
			ret['length'] = rows.length;
		}
	} else if (doc && doc.documentElement.tagName == 'ERRORS'){
		doc = executeQuery("select * from " + tName + " where 1=0", 0, 1);
		if (doc) {
			for (var i=0;i<doc.fields.length;i++) {
				var colName = doc.fields[i].name;
				ret[colName] = new Array();
				ret[colName]['name'] = colName;
				ret[colName]['data_type'] = doc.fields[i].type;
				ret[colName]['numeric'] = doc.fields[i].numeric?doc.fields[i].numeric:'';
				if (parseInt(ret[colName]['data_type']) == ret[colName]['data_type']) {
					ret[colName]['data_type'] = metaType(ret[colName]['data_type']);
				}
				ret[colName]['primary_key'] = doc.fields[i].primary_key;			
			}
			ret['length'] = doc.fields.length;
		}
	}
	window.status = 'Quering Server: DONE';
	return ret;
}

function getKeysOfTableCfm(tName) {
	return [];
}

function getKeysOfTable(tName) {
	window.status = 'Quering Server ...';

	if (serverExt == 'cfm') {
		return getKeysOfTableCfm(tName);
	}
	var ret = [];
	var doc = null;
	doc = xmlRequest('GetKeysOfTable&TableName=' + escape(tName));

	if (doc && doc.documentElement.tagName == 'RESULTSET') {
		var rows = doc.getElementsByTagName('FIELD');
		var fields = new Array();
		if (rows.length > 0) {
			for (var i=0;i<rows.length;i++) {
				fields[rows[i].text]=i+1;
			}
		}

		rows = doc.getElementsByTagName('ROW');
		if (rows.length > 0) {
			for (var i=0;i<rows.length;i++) {
				var colName = getData(fields, 'COLUMN_NAME', rows, i);
				ret[colName] = {};
				ret[colName]['name'] = colName;
				ret[colName]['data_type'] = getData(fields, 'DATA_TYPE', rows, i); 
				ret[colName]['is_nullable'] = getData(fields, 'IS_NULLABLE', rows, i); 
				ret[colName]['column_size'] = getData(fields, 'COLUMN_SIZE', rows, i); 
				ret[colName]['primary_key'] = 1; 

				if (parseInt(ret[colName]['data_type']) == ret[colName]['data_type']) {
					ret[colName]['data_type'] = metaType(ret[colName]['data_type']);
				}
			}
			ret['length'] = rows.length;
		}
	} else if (doc && doc.documentElement.tagName == 'ERRORS'){}
	window.status = 'Quering Server: DONE';
	return ret;
}
function executeQueryCfm(sql, maxRows, withHeaders, silent) {
	var ret = new Array();
	try {
		var arr = getPostCfm('SQLSTMNT', sql, 'SELECT');
	} catch(e) {
		if (!silent && !e.match(/qub_\w+_\w+/) || debug) {
			if (e) {
				alert(e);
			}
		}
		//var arr = new Array(new Array());
		window.status = 'Quering Server: DONE';
		return false;
	}

	var i, j, n;
	for (i=1;i<arr.length && i!=maxRows+1;i++) {
		n = ret.length;
		ret[n] = new Array();
		for (j=0;j<arr[i].length;j++) {
			ret[n][j] = arr[i][j];
		}
	}
	if (!withHeaders) {
		window.status = 'Quering Server: DONE';
		return ret;
	}
	var ret2 = new Array();
	for (i=0; i<arr[0].length; i++) {
		n = ret2.length;
		ret2[n] = new Object();
		ret2[n].name = arr[0][i];
		ret2[n].type = '';
		ret2[n].numeric = '';
		ret2[n].primary_key = '';
	}
	window.status = 'Quering Server: DONE';
	return {rows: ret, fields: ret2};
}
var SQLErrors = [];
function setSQLError(str) {
	SQLErrors.push(str);
}

function getSQLErrors() {
	return SQLErrors;
}

function clearSQLErrors() {
	SQLErrors = [];
}

function executeQuery(sql, maxRows, withHeaders, silent) {
	window.status = 'Quering Server ...';

	if (!maxRows) maxRows = 10000;
	if (!withHeaders) withHeaders = false;
	if (!maxRows && maxRows!=0) {
		maxRows = 10000;
	}
	if (typeof silent == 'undefined') {
		/*error reporting*/
		silent=false;
	}

	if (serverExt == 'cfm') {
		return executeQueryCfm(sql, maxRows, withHeaders, silent);
	}
	var n, i, j, fields, rows, ret, ret2, doc;
	ret = new Array();
	ret2 = new Array();
	doc = null;
	try {
		doc = xmlRequest('ExecuteSQL&SQL=' + escape(sql) + "&MaxRows=" + maxRows);
	} catch(e) {
		//alert(executeQuery.caller.toString());
	}
	if (doc && typeof doc.documentElement != 'undefined') {
		if (doc && doc.documentElement.tagName == 'RESULTSET') {
			rows = doc.getElementsByTagName('ROW');
			//on ASP_VBs sometimes the recordset returns all the rows, ignoring the rs.MaxRows=maxRows setting
			for (i=0;i<Math.min(rows.length, maxRows);i++) {
				var n = ret.length;
				ret[n] = [];
				for (j=0;j<rows[i].childNodes.length;j++) {
					ret[n][j] = top.ui.is.ns?rows[i].childNodes[j].textContent:rows[i].childNodes[j].text;
				}
			}
			if (withHeaders) {
				fields = doc.getElementsByTagName('FIELD');
				for (i=0; i<fields.length; i++) {
					n = ret2.length;
					ret2[n] = new Object();
					ret2[n].name = fields[i].childNodes[0].text;
					ret2[n].type = fields[i].getAttribute("type");
					ret2[n].numeric = fields[i].getAttribute("numeric");
					ret2[n].primary_key = fields[i].getAttribute("primary_key");
				}
				window.status = 'Quering Server: DONE';
				return {rows: ret, fields: ret2};
			}
		}

		if (doc.documentElement.tagName == "RESULTSET") {
			window.status = 'Quering Server: DONE';
			return ret;
		} else if (doc.documentElement.tagName == "ERRORS") {
			var tmp = doc.documentElement.text;//doc.getElementsByTagName("DESCRIPTION")[0].text;
			if (!tmp.match(/qub_\w+_\w+/) || debug) {
				if (!tmp.match(/ResultSet is from UPDATE/) || debug) {
					if (!tmp.match(/no resultset was produced/i) || debug) {
						if (tmp) {
							setSQLError(tmp);
							if(!silent) {
								alert("Error:"+tmp);
							}
						}
					}
				}
			}
			var errors_nodes = doc.documentElement.getElementsByTagName("ERROR");
			if (errors_nodes.length) {
				for (var i=0; i<errors_nodes.length; i++) {
					var one_error_node = errors_nodes[i];
					var err_code = one_error_node.getAttribute("Identification");
					switch( err_code ){
					case "1111": {
						/* must change the use_asname setting to true*/
						alert("Change the \"Use AS in order by\" clause to \"true\" in settings.");
						break;
					}
					}/*end error code switch*/
				}
			}
			window.status = 'Quering Server: DONE';
			return false;
		}
	} else {
		if (doc) {
			window.status = 'Quering Server: DONE';
			return doc;
		}
		//alert("SQL error.");
		window.status = 'Quering Server: DONE';
		return false;
	}
}

function serialize(arr) {
	var i, ret = arr.length + ":";
	for (i=0;i<arr.length;i++) {
		ret += 'STR:' + arr[i].length + ':' + arr[i];
	}
	return ret;
}

function deserialize(str) {
	var arr = new Array();
	var idx=str.indexOf(":");
	var n = str.substring(0, idx++);
	if (n == -1) {
		var tmp = str.indexOf("\n");
		if (!str.match(/no resultset set was produced/i) && !str.match(/resultset is from update. no data/i)) {
			/*"resultset is from update. no data" generated by updates on ColdFusion*/
			setSQLError("Server Error:\n" + str.substring(idx, tmp));
			throw "Server Error:\n" + str.substring(idx, tmp);
		} else {
			throw '';
		}
	}
	var i, tmp, m, j, els;
	for (i=0;i<n;i++) {
		arr[i] = new Array();
		tmp = str.indexOf(":", idx);
		m = str.substring(idx, tmp);
		idx = tmp+1;
		tmp = str.substr(idx, m);
		idx += parseInt(m);
		tmp = tmp.replace(/,$/, '');
		tmp = tmp.replace(/""/g, '"_NOTHING_"');
		var els = tmp.split(/","/);
		els[0] = els[0].replace(/^"/, '');
		els[els.length-1] = els[els.length-1].replace(/"$/, '');
		for (j=0;j<els.length;j++) {
			arr[i][j] = els[j].replace(/^_NOTHING_$/, '');
		}
	}
	return arr;
}

function getPostCfm(command, param1, param2) {
	if (!param1) {
		param1 = '';
	}
	if (!param2) {
		param2 = '';
	}
	var elements, response, xmlhttp, i;
	try {
		xmlhttp=XmlHttp.create();
	} catch (e) {
		xmlhttp=false
	}

	var postStringCF = serialize([postString, command, param1, param2, Password]);

	xmlhttp.open("POST", "/CFIDE/main/ide.cfm?CFSRV=IDE&ACTION=DBFuncs", false);
	xmlhttp.setRequestHeader("Content-Type", "application/x-ColdFusionIDE");
	xmlhttp.setRequestHeader("Timeout", "1");
	xmlhttp.send(postStringCF);
	response = deserialize(xmlhttp.responseText);
	return response;
}

function getData(fields, fname, rows, i) {
	if (fields[fname]) {
		return rows[i].childNodes[fields[fname]-1].text;
	} else {
		return '';
	}
}
function metaType2(cType) {
	var retVal = cType;

	var a = new Array();
	//from the ASP book
	a["empty"] = 0;
	a["smallint"] = 2;
	a["integer"] = 3;
	a["single"] = 4;
	a["double"] = 5;
	a["currency"] = 6;
	a["date"] = 7;
	a["bstr"] = 8;
	a["idispatch"] = 9;
	a["error"] = 10;
	a["boolean"] = 11;
	a["variant"] = 12;
	a["iunknown"] = 13;
	a["decimal"] = 14;
	a["tinyint"] = 16;
	a["unsignedtinyint"] = 17;
	a["unsignedsmallint"] = 18;
	a["unsignedint"] = 19;
	a["bigint"] = 20;
	a["ebigint"] = 20; // matched to bigint
	a["unsignedbigint"] = 21;
	a["guid"] = 72;
	a["binary"] = 128;
	a["char"] = 129;
	a["wchar"] = 130;
	a["numeric"] = 131;
	a["varnumeric"] = 131;
	a["userdefined"] = 132;
	a["dbdate"] = 133;
	a["dbtime"] = 134;
	a["dbtimestamp"] = 135;
	a["varchar"] = 200;
	a["longchar"] = 201;
	a["longvarchar"] = 201;
	a["memo"] = 201;
	a["varwchar"] = 202;
	a["string"]=201;
	a["longvarwchar"] = 203;
	a["varbinary"] = 204;
	a["longvarbinary"] = 204; 
	a["longbinary"] = 205; // matched to longvarbinary

	//others
	a["money"] = 6;
	a["int"] = 3; //integer
	a["counter"] = 131; //numeric
	a["logical"] = 901; //bit
	a["byte"] = 16; //tinyint

	// firebird 
	a["varying"] = 200; //varchar

	//oracle
	a["varchar2"] = 200;
	a["smalldatetime"] = 135;
	a["datetime"] = 135;
	a["number"] = 5; //double
	a["ref cursor"] = 900; //Arbitrary ID Val
	a["refcursor"] = 900;
	a["bit"] = 901; //Arbitrary ID Val;
	a["long raw"] = 20; // Match it to BigInt
	a["clob"] = 129; //char
	a["long"] = 20; // bigint
	a["double precision"] = 131; //numeric
	a["raw"]  = 204;// Match it to Binary
	a["nclob"]  = 204;//Match it to Binary
	a["bfile"]  = 204;//Match it to Binary
	a["rowid"]  = 129 ;//Match it to Hexadecimal String 
	a["urowid"] = 129 ;//Match it to Hexadecimal String

	//odbc
	a["empid"] = 129; //char
	a["tid"] = 129;
	a["bit"] = 901; 
	a["id"] = 200; //varchar

	// SQL Server 7
	a["smallmoney"] = 6; //currency
	a["float"] = 5; //double
	a["nchar"] = 200; //varchar
	a["real"] = 131; //numeric
	a["text"] = 200; //varchar
	a["blob"] = 200       // matched to text
	a["tinyblob"] = 200   // matched to text
	a["mediumblob"] = 200 // matched to text
	a["longblob"]  = 200  // matched to text
	a["timestamp"] = 135; //numeric
	a["sysname"] = 129;
	a["int identity"] = 131; //numeric counter
	a["smallint identity"] = 131; //numeric counter
	a["tinyint identity"] = 131; //numeric counter
	a["bigint identity"] = 131; //numeric counter
	a["decimal() identity"] = 131; //numeric counter
	a["numeric() identity"] = 131; //numeric counter
	a["uniqueidentifier"] = 131;//numeric
	a["ntext"]  = 200; //varchar
	a["nvarchar"] = 200; //varchar
	a["nvarchar2"] = 200; //varchar
	a["image"]  =  204 ;// binary

	// DB2
	a["time"] = 135; // needs '
	a["character () for bit data"] = 129;
	// the following entries are already defined to 200 for SQL Server
	//a["blob"] = 128; //binary
	//a["tinyblob"] = 128; //binary
	//a["mediumblob"] = 128; //binary
	//a["longblob"] = 128; //binary
	a["long varchar for bit data"] = 200; //varchar
	a["varchar () for bit data"] = 200; //varchar
	a["long varchar"] = 131; //numeric
	a["character"] = 129; //char

	//JDBC Specifc constants
	a["-8"] = 200; //JDBC varchar
	a["-9"] = 200; //JDBC varchar
	a["-10"] = 200; //JDBC varchar
	a["other"] = 200; //JDBC varchar

	//MySQL
	//a["year"] = 133; //dbdate
	a["tinytext"] = 200; //varchar
	a["mediumtext"] = 200; //varchar
	a["longtext"] = 201; //longvarchar
	a["mediumint"] = 3; //integer
	a["enum"] = 200; //var char
	a["int unsigned"] = 19;
	a["tinyint unsigned"] = 19;
	a["year"] = 3;
	//a["set"] = 132;
	
	//PostgreSQL
	a["abstime"] = 135; //
	a["aclitem"] = 132; //userdefined
	a["box"] = 	200; //varchar
	a["bytea"] = 3;		//integer
	a["varbit"] = 3;	//integer
	a["float8"] = 5; //double
	a["float4"] = 5; //double
	a["real"] = 5; //double
	a["int8"] = 3; //integer
	a["int4"] = 3; //integer
	a["int2vector"] = 3; //integer
	a["int2"] = 2; //small int
	a["timestamptz"] = 135;
	a["bool"] = 200; //varchar
	a["bpchar"] = 200; //varchar
	a["serial"] = 2; //small int
	a["character_data"] = 200; //varchar
	a["cardinal_number"] = 3; //varchar
	
	a["set"] = 132; //userdefined
	a["double unsigned zerofill"] = 5; //double
	a["float unsigned zerofill"] = 5; //double
	
	//Access
	a["i"] = 4;
	a['I'] = 3;
	a["c"] = 8;
	a["C"] = 200;
	a["N"] = 3;
	a["n"] = 200;
	a["d"] = 7;
	a["L"] = 11;
	a["x"] = 200;
	a["X"] = 200;
	a["T"] = 135;
	a["t"] = 135;
	a["R"] = 131;
	a["B"] = 205;

	if (typeof(a[cType]) != 'undefined') {
		retVal = a[cType];
	} else {
		cType = cType.toLowerCase();
		cType = cType.replace(/\s*unsigned/i,"");
		cType = cType.replace(/\s*zerofill/i,"");
		if (typeof(a[cType]) != 'undefined') {
			retVal = a[cType];
		}
	}

	return retVal;
}
function metaType(nType) {
	var ret = 'unknown ' + nType;
	switch (parseInt(nType)) {
		case 200:
		case 201:
			ret = "varchar";
			break;
		case 204:
			ret = "image";
		case 205:
			ret = "blob";
			break;
		case 2:
			ret = "smalint";
			break;
		case 3:
			ret = "int";
			break;
		case 4:
			ret = "single";
			break;
		case 5:
			ret = "real";
			break;
		case 16:
		case 17:
			ret = "tinyint";
			break;
		case 901:
		case 11:
			ret = "bit";
			break;
		case 130:
			ret = "nchar";
			break;
		case 7:
			ret = "date";
			break;
		case 6:
			ret = "money";
			break;
		case 131:
		case 3:
			ret = "int";
			break;
		case 5:
			ret = "float";
			break;
		case 14:
			ret = "decimal";
			break;
		case 132:
			ret = "set";
			break;
		case 133:
		case 135:
			ret = "datetime";
			break;
		case 129:
			ret = "text";
			break;
		case 20:
			ret = "bigint";
			break;
		case 128:
			ret = "binary";
			break;
		case 72:
			ret = "uniqueid";
			break;
	}
	return ret;
}


//MsXML on Mozilla

function getDomDocumentPrefix() {
	if (getDomDocumentPrefix.prefix)
		return getDomDocumentPrefix.prefix;
	
	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;
	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".DomDocument");
			return getDomDocumentPrefix.prefix = prefixes[i];
		}
		catch (ex) {};
	}
	
	throw new Error("Could not find an installed XML parser");
}

function getXmlHttpPrefix() {
	if (getXmlHttpPrefix.prefix)
		return getXmlHttpPrefix.prefix;
	
	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;
	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".XmlHttp");
			return getXmlHttpPrefix.prefix = prefixes[i];
		}
		catch (ex) {};
	}
	
	throw new Error("Could not find an installed XML parser");
}

// XmlHttp factory
function XmlHttp() {}

XmlHttp.create = function () {
	try {
		if (window.XMLHttpRequest) {
			var req = new XMLHttpRequest();
			
			// some versions of Moz do not support the readyState property
			// and the onreadystate event so we patch it!
			if (req.readyState == null) {
				req.readyState = 1;
				req.addEventListener("load", function () {
					req.readyState = 4;
					if (typeof req.onreadystatechange == "function")
						req.onreadystatechange();
				}, false);
			}
			
			return req;
		}
		if (window.ActiveXObject) {
			var ax = new ActiveXObject(getXmlHttpPrefix() + ".XmlHttp");
			return ax;
		}
	}
	catch (ex) {
	}
	// fell through
	throw new Error("Your browser does not support XmlHttp objects");
};

// XmlDocument factory
function XmlDocument() {}

XmlDocument.create = function () {
	try {
		// DOM2
		if (document.implementation && document.implementation.createDocument) {
			var doc = document.implementation.createDocument("", "", null);
			
			// some versions of Moz do not support the readyState property
			// and the onreadystate event so we patch it!
			if (doc.readyState == null) {
				doc.readyState = 1;
				doc.addEventListener("load", function () {
					doc.readyState = 4;
					if (typeof doc.onreadystatechange == "function")
						doc.onreadystatechange();
				}, false);
			}
			
			return doc;
		}
		if (window.ActiveXObject)
			return new ActiveXObject(getDomDocumentPrefix() + ".DomDocument");
	}
	catch (ex) {}
	throw new Error("Your browser does not support XmlDocument objects");
};

// Create the loadXML method and xml getter for Mozilla
if (window.DOMParser &&
	window.XMLSerializer &&
	window.Node && Node.prototype && Node.prototype.__defineGetter__) {

	// XMLDocument did not extend the Document interface in some versions
	// of Mozilla. Extend both!
	//XMLDocument.prototype.loadXML = 
	Document.prototype.loadXML = function (s) {
		
		// parse the string to a new doc	
		var doc2 = (new DOMParser()).parseFromString(s, "text/xml");
		
		// remove all initial children
		while (this.hasChildNodes())
			this.removeChild(this.lastChild);
			
		// insert and import nodes
		var ret = false;
		for (var i = 0; i < doc2.childNodes.length; i++) {
			this.appendChild(this.importNode(doc2.childNodes[i], true));
			ret = true;
		}
		return ret;
	};
	
	
	/*
	 * xml getter
	 *
	 * This serializes the DOM tree to an XML String
	 *
	 * Usage: var sXml = oNode.xml
	 *
	 */
	// XMLDocument did not extend the Document interface in some versions
	// of Mozilla. Extend both!
	/*
	XMLDocument.prototype.__defineGetter__("xml", function () {
		return (new XMLSerializer()).serializeToString(this);
	});
	*/
/*@cc_on @*/
/*@if (@_jscript_version >= 3)
	//hide the next block of code from the IE compiler ;)
@else @*/
	documentProto =	Document.prototype;
	documentProto.__proto__ = {
		get xml() {
			return (new XMLSerializer()).serializeToString(this);
		},
		__proto__: documentProto.__proto__
	}
	
	elementProto = Element.prototype;
	elementProto.__proto__ = {
		get text() { 
			return (new XMLSerializer()).serializeToString(this).replace(/<[^>]*>/g, '');
		},
		get innerText() { 
			return (new XMLSerializer()).serializeToString(this).replace(/<[^>]*>/g, '');
		},
		set innerText(new_value) { 
			var tn = this.ownerDocument.createTextNode(new_value);
			this.innerHTML = "";
			this.appendChild(tn);
		},
		__proto__: elementProto.__proto__
	};

/*@end @*/
/*
	var documentProto = Document.prototype;
	var documentGrandProto = documentProto.__proto__ = {
		__proto__: documentProto.__proto__
	};
	documentGrandProto.__defineGetter__('xml',
		function () {
			return (new XMLSerializer()).serializeToString(this);
		}
	);


	var elementProto = Element.prototype;
	var elementGrandProto = elementProto.__proto__ = {
		__proto__: elementProto.__proto__
	};
	elementGrandProto.__defineGetter__('text',
		function () {
			return (new XMLSerializer()).serializeToString(this).replace(/<[^>]*>/g, '');
		}
	);
	elementGrandProto.__defineGetter__('innerText',
		function () {
			return (new XMLSerializer()).serializeToString(this).replace(/<[^>]*>/g, '');
		}
	);

	elementGrandProto.__defineSetter__('innerText',
		function (new_value) {
			var tn = this.ownerDocument.createTextNode(new_value);
			this.innerHTML = "";
			this.appendChild(tn);
		}
	);
*/

}
