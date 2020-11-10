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

var addTables = new Array();
var sqlQuery = "";
var sqlQueryRT = "";

var g_tables, g_tableAliases, g_tableNames;

if (window.qs) {
	serverExt = getServerExtension();
} else {
	if (window.parent && window.parent.qs) {
		serverExt = window.parent.getServerExtension();
	} else {
		if (window.opener && window.opener.qs) {
			serverExt = window.opener.getServerExtension();
		} else {
			if (window.opener && window.opener.parent && window.opener.parent.qs) {
				serverExt = window.opener.parent.getServerExtension();
			}
		}
	}
}
var technology = serverExt.toUpperCase();

function genSql(func) {
	if (top.ui.loaded && top.ui.sqlQueryRebuild) {
		g_tables = [];
		g_tableAliases = [];
		g_tableNames = [];
		
		var arr = myQuery.tables;
		var j = 0;

		//	step 0 : fill tables, table aliases, table indexes ( reorder later by number of relations)
		Array_each(arr, function(not_used, i) {
			g_tables[arr.item(i).name] = j++;
			g_tableAliases[arr.item(i).name] = arr.item(i).name;
			g_tableNames[arr.item(i).name] = arr.item(i).table_name;
		});

		//	step 1 : links
		arr = myQuery.relations;
		var myLinks = [];
		Array_each(arr, function(not_used, i) {
			if (!myLinks[arr.item(i).table1]) {
				myLinks[arr.item(i).table1] = [];
			}
			if (!myLinks[arr.item(i).table2]) {
				myLinks[arr.item(i).table2] = [];
			}
			myLinks[arr.item(i).table1].push(arr.item(i));
			myLinks[arr.item(i).table2].push(arr.item(i));
		});

		k = j;
		
		for (i in g_tables) {
			if (!myLinks[i]) {
				g_tables[i] = k;
			}
		}

		//	step 2 : reorder by relations !!!!
		arr = myQuery.relations;
		Array_each(arr, function(not_used, i) {
			Array_each(arr, function(not_used_2, j) {
				if (arr.item(j).card1 == "1" && arr.item(j).card2 == "n") {
					if (g_tables[arr.item(j).table1] > g_tables[arr.item(j).table2]) {
						//switch
						var temp = g_tables[arr.item(j).table1];
						g_tables[arr.item(j).table1] = g_tables[arr.item(j).table2];
						g_tables[arr.item(j).table2] = temp;
					}
				}
				if (arr.item(j).card1 == "n" && arr.item(j).card2 == "1") {
					if (g_tables[arr.item(j).table2] > g_tables[arr.item(j).table1]) {
						//switch
						var temp = g_tables[arr.item(j).table1];
						g_tables[arr.item(j).table1] = g_tables[arr.item(j).table2];
						g_tables[arr.item(j).table2] = temp;
					}
				}
			});
		});

		//	step 3 : reorder by number of relations
		t1 = [];
		j=0;
		for (i in g_tables) {
			t1[j] = i;
			j++;
		}
		
		for (i=0; i < t1.length; i++) {
			for (j=i+1; j < t1.length; j++) {
				if (g_tables[t1[i]] > g_tables[t1[j]]) {
					var tmp = t1[i];
					t1[i] = t1[j];
					t1[j] = tmp;
				}
			}
		}

		a=1;
		for (i=1; i < t1.length; i++) {
			l = myLinks[t1[i]];
			if (!l) {
				continue;
			}
			//for all the relations for this table !!!
			for (j=0; j < l.length; j++) {
				//if is FROM THIS TABLE
				if (l[j].table1 == t1[i]) {
					for (k=0; k < i; k++) {
						if (l[j].table2 == t1[k]) {
							break;
						}
					}
					if (k == i) { continue; }	//the OTHER table was not found
					break;
				} else if (l[j].table2 == t1[i]) {
					for (k=0; k < i; k++) {
						if (l[j].table1 == t1[k]) {
							break;
						}
					}
					if (k == i) { continue; }	// the other TABLE was not found
					break;
				}
			}

			if (j == l.length) {
				if (i+a >= t1.length) {
					break;
				}
				temp = t1[i+a];
				t1[i+a] = t1[i];
				t1[i] = temp;
				i--; a++;
			} else {
				a=1;
			}
		}
		//alert(t1);
		generateSql(t1, myLinks);
//		top.ui.invalidate(null, null);
	}
	if (func) {
		eval(func);
	}
}

function generateSql(tbl, myLinks) {
	var sql = "", sql1 = "", sqlRT = "", select = "", where = "", whereRT = "", group = "", 
		altgroup = "", having = "", havingRT = "", orderBy = "", as = "", arg4 = "";

	if (typeof use_asname == 'undefined') {
		getSettings();
	}
	//do not include same column twice in the grouping conditions
	//mark already grouped conditions in grouped_columns array
	var grouped_columns = [];

	var selectedCols = [];
	Array_each(myQuery.tables, function(not_used, i) {
		var table = myQuery.tables.item(i);
		Array_each(table.columns, function(not_used, j) {
			if (table.columns.item(j).checked) {
				Array_push(selectedCols, table.columns.item(j));
			}
		});
	});

	for (i=0; i < selectedCols.length; i++) {
		for (j=i+1; j < selectedCols.length; j++) {
			if (selectedCols[j].cindex < selectedCols[i].cindex) {
				var tmp = selectedCols[j];
				selectedCols[j] = selectedCols[i];
				selectedCols[i] = tmp;
			}
		}
	}

	g_hasAggFunc = false;

	for (i=0; i < selectedCols.length; i++) {
		var tableName = selectedCols[i].table.name;
		var columnName = selectedCols[i].column_name;
		var as         = selectedCols[i].name;
		var aggFunc    = selectedCols[i].aggFunc;
		var order      = selectedCols[i].order;
		var output = selectedCols[i].output;
		var fullyQualified = tableName + '.' + columnName;

		var aggExpression = aggFunc + '(' + fullyQualified + ') ';

		//order by name_emp
		if (order) {
			if (aggFunc) {
				if (orderBy != "") {
					orderBy += ", ";
				}
				if (use_asname == "true") {
					orderBy += as + " " + order;
				} else {
					orderBy += aggExpression + order;
				}
			} else {
				if (orderBy != "") {
					orderBy += ", ";
				}
				orderBy += fullyQualified + " " + order;
			}
		}

		if (output) {
			if (select != "") {
				select += ", ";
			}
		}
		//select name_emp | select count(name_emp) as countname_emp | select 
		if (aggFunc) {
			g_hasAggFunc = true;
			if (!as) {
				as = (aggFunc + columnName).replace(/[^\w]/g, "");
			}
			if (output) {
				select += aggExpression + ' AS ' + as;
			}
		} else {
			if (output) {
				select += fullyQualified;
				if (as && as != columnName) {
					select += " AS " + as;
				}
			}
			if (!grouped_columns[fullyQualified] && columnName!="*") {
				if (group != "") {
					group+=", ";
				}
				group += fullyQualified;
				grouped_columns[fullyQualified] = 1;
			}
		}
		
	}
	oldWCheck = 0;
	wCheck = 0;
	wAggregate = 0;
	
	whereCond = [];
	Array_each(myQuery.tables, function(not_used, i) {
		var table = myQuery.tables.item(i);
		Array_each(table.columns, function(not_used, j) {
			if (typeof table.columns.item(j).condition != 'undefined'
				&&(	table.columns.item(j).condition.string != '' 
						||
						table.columns.item(j).condition.cond_type != '' 
					)
					) {
				Array_push(whereCond, table.columns.item(j).condition);
			}
		});
	});

	for (i=0; i < whereCond.length; i++) {
		for (j=i+1; j < whereCond.length; j++) {
			if (whereCond[j].column.cindex < whereCond[i].column.cindex) {
				var tmp = whereCond[j];
				whereCond[j] = whereCond[i];
				whereCond[i] = tmp;
			}
		}
	}

	for (j=0; j < whereCond.length; j++) {
		/*
		tdType = selectedCols[whereCond[j].selColNo].tdType;
		aggFunc = selectedCols[whereCond[j].selColNo].aggFunc;
		*/
		tdSep  = whereCond[j].column.sep;
		//tableName + '.' + columnName
		wColumn    = whereCond[j].column.table.name + '.' + whereCond[j].column.column_name;
		var ctv = (whereCond[j].cond_type == '<>' || whereCond[j].cond_type == '!=') ? notequals:whereCond[j].cond_type;
		wCond      = ctv;
		wCondRT    = ctv;
		wTestVal   = whereCond[j].test_value;
		wParType   = whereCond[j].var_type;
		wParam     = whereCond[j].param_name;
		wString = whereCond[j].string;
		wAggFunc = whereCond[j].column.aggFunc;

		var wAggCond = (typeof wAggFunc != 'undefined' && wAggFunc != null && wAggFunc != '') ? 1 : 0;
		wAndOr     = '';
		
		if(wCheck && wAggregate != wAggCond) {
			where += ")";
			whereRT += ")";
			oldWCheck = 0;
		}
		wAggregate = wAggCond;
		wCheck     = parseInt(whereCond[j].check);
		if (wAndOr == "") {
			wAndOr = " AND ";
		}

		if (!wAndOr.match(/^ /)) {
			wAndOr = " " + wAndOr;
		}
		if (!wAndOr.match(/ $/)) {
			wAndOr += " ";
		}

		if (wCond != '') {
			switch (tdSep) {
				case '1':
					sep = wAggFunc == "count" ? "" : "'";
					break;
				case '2':
					sep = dateseparator;
					break;
				default:
					sep = '';
			}

			switch (wCond) {
				case 'contains':
					bop = " LIKE '%";
					eop = "%'";
					break;
				case 'begins with':
					bop = " LIKE '";
					eop = "%'";
					break;
				case 'ends with':
					bop = " LIKE '%";
					eop = "'";
					break;
				case 'is null':
					bop = " is null";
					eop = "";
					break;
				case 'is not null':
					bop = " is not null";
					eop = "";
					break;
				default:
					bop = wCond + sep;
					eop = sep;
			}
			wCond = bop;
			wCondRT = bop;

			if (wParType != '' && wParam != '') {
				if (wCond == "") {
					continue;
				}
				if (wCond != " is null" && wCond != " is not null") {
					wCondRT += wTestVal;
					wCond += getLanguageVariable(wParType, sep, wParam);
					wCond += eop;
					wCondRT += eop;
				}
		
				if (oldWCheck != wCheck) {
					if (wCheck == 1) {
						wColumn = "(" + wColumn;
					}
				}
			} else {
				if (wCond == " is null" || wCond == " is not null") {
					//alert(wCond + ', ' + wCondRT + ', ' + wColumn);
					//wCond = wColumn + wCond;
					//wCondRT = wColumn + wCondRT;
				}
			}
		} else {
			wColumn = '';
			wCond = wString;
			wCondRT = wString;
		}
		
		if (wAggregate == 1) {
			if (having != "") {
				if (oldWCheck != wCheck && wCheck == 0) {
					having += ")" + wAndOr;
					havingRT += ")" + wAndOr;
				} else {
					having += wAndOr;
					havingRT += wAndOr;
				}
			}
			having += wAggFunc + '(' + wColumn + ')' + wCond;
			havingRT += wAggFunc + '(' + wColumn + ')' + wCondRT;
		} else {
			if (where != "") {
				if (oldWCheck != wCheck && wCheck == 0) {
					where += ")" + wAndOr;
					whereRT += ")" + wAndOr;
				} else {
					where += wAndOr;
					whereRT += wAndOr;
				}
			}
			where += wColumn + wCond + "\r\n";
			whereRT += wColumn + wCondRT;
		}
		oldWCheck = wCheck;
	}

	if (wCheck) {
		if (wAggregate == 1) {
			having += ")";
			havingRT += ")";
		} else {
			where += ")";
			whereRT += ")";
		}
	}

	//alert(g_hasAggFunc);

	var arr = [];
	Array_each(myQuery.tables, function(not_used, i) {
		var table = myQuery.tables.item(i);
		if (table.select_all) {
			if (g_hasAggFunc) {
				for (var j=0; j<table.columns.length; j++) {
					if (table.columns.item(j).name == "*") {
						continue;
					}
					var fullyQualified = table.name + '.' + table.columns.item(j).name;
					if (!grouped_columns[fullyQualified]) {
						if (group != "") {
							group+=", ";
						}
						group += fullyQualified;
						grouped_columns[fullyQualified] = 1;
					}
				}
			}
		}
	});
	if (arr.length > 0) {
		select += (select != '' ? ', ' : '') + arr.join(', ');
	}

	if (select == "") {
		select = "*";
	}
	select1 = select.replace(/ AS [^, ]+/g, "");
	//alert(select1 +"\r\n---\r\n"+group + "\r\n---\r\n"+having);
	//TODO : verificat cu functiile agregate
	if (!g_hasAggFunc && having == "") {
		group = "";
	} else {
		if (altgroup != "") {
			if (group != "") {
				group += ", " + altgroup;
			} else {
				group = altgroup;
			}
		}
	}

	top.ui.sql_warnings = [];
	myt = [];
	myUsedLinks = [];
	var i;
	for (i=0; i < tbl.length; i++) {
		if (sql == sql1) {
			sql += escapeTableName(tbl[i], 1) + "\n";
		} else {
			l = myLinks[tbl[i]];
			// unlinked tables alert
			if (!l) {
				window.sqlQuery = "";
				window.sqlQueryRT = "";
				top.ui.sql_warnings[top.ui.sql_warnings.length] = locales["Table"]+" "+tbl[i]+" "+locales["is unlinked and used."];
				top.ui.invalidate(true);
				return false;
			}
			for (j=0;j<l.length;j++) {
				if (l[j].table1 == tbl[i]) {
					for (k=0; k<i; k++) {
						if (l[j].table2 == tbl[k]) {
							break;
						}
					}
					if (k == i) { continue; }
					break;
				} else if (l[j].table2 == tbl[i]) {
					for (k=0; k<i; k++) {
						if (l[j].table1 == tbl[k]) {
							break;
						}
					}
					if (k == i) { continue; }
					break;
				}
			}
			if (j == l.length) {
				window.sqlQuery = "";
				window.sqlQueryRT = "";
				top.ui.sql_warnings[top.ui.sql_warnings.length] = locales["Table"]+" "+tbl[i]+" "+locales["is unlinked and used."];
				top.ui.invalidate(true);
				return false;
			} else {
				sql = "("+sql;
				if (l[j].restrict == 'no') {
					if (l[j].card1 == "1" && l[j].card2 == "n") {
						if (l[j].table1 == tbl[i]) {
							sql += "RIGHT";
						} else {
							sql += "LEFT";
						}
					} else if (l[j].card1 == "n" && l[j].card2 == "1") {
						if (l[j].table1 == tbl[i]) {
							sql += "LEFT";
						} else {
							sql += "RIGHT";
						}
					}/* else if (l[j].card1 == "1" && l[j].card2 == "1") {
						sql += "INNER";
					}*/
				} else {
					sql += "INNER";
				}
				l[j].sqlUsed=2;
				if (l[j].table1 == tbl[i]) {
					sql+=" JOIN "+escapeTableName(l[j].table1, 1)+" ON "+escapeTableName(l[j].table1, 2)+"."+l[j].field1+"="+escapeTableName(l[j].table2, 2)+"."+l[j].field2+")\n";
				} else {
					sql+=" JOIN "+escapeTableName(l[j].table2, 1)+" ON "+escapeTableName(l[j].table2, 2)+"."+l[j].field2+"="+escapeTableName(l[j].table1, 2)+"."+l[j].field1+")\n";
				}
			}
		}
	}

	while (addTables.length) {
		addTables.pop();
	}
	
	rels = myQuery.relations;

	for (i=0;i<rels.length;i++) {
		if (rels.item(i).sqlUsed!=2) {
			if (rels.item(i).card1=="n") {
				k = 1;
				for (j=0;j<addTables.length;j++) {
					var re = new RegExp(rels.item(i).table2+"[0-9]");
					if (re.test(addTables[j])) {
						k++;
					}
				}
				addTables.push(rels.item(i).table2+k);
				sql = "("+sql;
				if (rels.item(i).restrict == "no") {
					sql+="LEFT";
				} else {
					sql+="INNER";
				}
				sql += " JOIN "+escapeTableName(rels.item(i).table2, 1)+" on "+escapeTableName(rels.item(i).table2+k, 2)+"."+rels.item(i).field2+"="+escapeTableName(rels.item(i).table1, 2)+"."+rels.item(i).field1+")\n";
			} else if (rels.item(i).card2=="n") {
				k = 1;
				for (j=0;j<addTables.length;j++) {
					var re = new RegExp(rels.item(i).table1+"[0-9]");
					if (re.test(addTables[j])) {
						k++;
					}
				}
				addTables.push(rels.item(i).table1+k);
				sql = "("+sql;
				if (rels.item(i).restrict == "no") {
					sql+="LEFT";
				} else {
					sql+="INNER";
				}
				sql+=" JOIN "+escapeTableName(rels.item(i).table1, 1)+" on "+escapeTableName(rels.item(i).table1+k, 2)+"."+rels.item(i).field1+"="+escapeTableName(rels.item(i).table2, 2)+"."+rels.item(i).field2+")\n";
			} else {
				break;
			}
		}
	}

	for (k=0;k<rels.length;k++) {
		rels.item(k).sqlUsed=0;
	}
	if (i!=rels.length) {
		alert(locales["Circular reference detected."]);
		top.ui.invalidate(true);
		return false;
	}
	sql = "SELECT "+((myQuery && myQuery.distinct)? 'DISTINCT ' :'') + select + "\nFROM " + sql;
	sqlRT = sql;
	if (where) {
		sql+="WHERE " + where + "\n";
		sqlRT+="WHERE " + whereRT + "\n";
	}
	if (group) {
		sql+="GROUP BY " + group + "\n";
		sqlRT+="GROUP BY " + group + "\n";
	}
	if (having) {
		sql+="HAVING "+having+"\n";
		sqlRT+="HAVING "+havingRT+"\n";
	}
	if (orderBy) {
		sql+="ORDER BY "+orderBy+"\n";
		sqlRT+="ORDER BY "+orderBy+"\n";
	}
	window.sqlQuery = sql;
	window.sqlQueryRT = sqlRT;
	top.ui.invalidate(false);
	return true;
}

function getLanguageVariable(wParType, tdType, wParam) {
	switch(technology) {
		case 'PHP':
			switch (wParType) {
				case 'Form Variable':
					return '".$_POST["'+wParam+'"]."';
					break;
				case 'URL Variable':
					return '".$_GET["'+wParam+'"]."';
					break;
				case 'Cookie Variable':
					return '".$_COOKIE["'+wParam+'"]."';
					break;
				case 'Session Variable':
					return '".$_SESSION["'+wParam+'"]."';
					break;
				case 'Entered Value':
					return wParam;
					break;
			}
			break;
		case 'ASP':
			switch (wParType) {
				case 'Form Variable':
					return '"+Request.Form("'+wParam+'")+"';
					break;
				case 'URL Variable':
					return '"+Request.QueryString("'+wParam+'")+"';
					break;
				case 'Cookie Variable':
					return '"+Request.Cookie("'+wParam+'")+"';
					break;
				case 'Session Variable':
					return '"+Session("'+wParam+'")+"';
					break;
				case 'Entered Value':
					return wParam;
					break;
			}
			break;
		case 'CFM':
			switch (wParType) {
				case 'Form Variable':
					return '#FORM.'+wParam+'#';
					break;
				case 'URL Variable':
					return '#URL.'+wParam+'#';
					break;
				case 'Cookie Variable':
					return '#COOKIE.'+wParam+'#';
					break;
				case 'Session Variable':
					return '#SESSION.'+wParam+'#';
					break;
				case 'Entered Value':
					return wParam;
					break;
			}
			break;
		case 'JSP':
			switch (wParType) {
				case 'Form Variable':
				case 'URL Variable':
				case 'Cookie Variable':
					return '"+request.getParameter("'+wParam+'")+"';
					break;
				case 'Session Variable':
					return '"+session.getValue("'+wParam+'")+"';
					break;
				case 'Entered Value':
					return wParam;
					break;
			}
			break;
	}
}

function getSettings() {
	var x = "";
	var x = executeQuery("select * from qub3_settings_set");

	if (x !== false && x.length != 0) {
		for(var i=0; i<x.length; i++) {
			eval(x[i][0]+"=\""+ unescape(x[i][1])+"\"");
		}
	}
	switch (technology) {
		case "PHP":
			dateseparator = typeof(dateseparator) != "undefined" ? dateseparator: "'";
			notequals = typeof(notequals) != "undefined" ? notequals : "!=";
			use_asname = typeof(use_asname) != "undefined" ? use_asname:"false";
			break;
		case "CFM":
			dateseparator = typeof(dateseparator)!="undefined"?dateseparator:"###";
			notequals = typeof(notequals)!="undefined"?notequals:"<>";
			use_asname = typeof(use_asname)!="undefined"?use_asname:"true";
			break;
		case "ASP":
			dateseparator = typeof(dateseparator)!="undefined"?dateseparator:"#";
			notequals = typeof(notequals)!="undefined"?notequals:"<>";
			use_asname = typeof(use_asname)!="undefined"?use_asname:"false";
			break;
		default:
			dateseparator = typeof(dateseparator)!="undefined"?dateseparator:"'";
			notequals = typeof(notequals)!="undefined"?notequals:"!=";
			use_asname = typeof(use_asname)!="undefined"?use_asname:"true";
	}
}

function escapeTableName(tName, tAlias) {
	var ret = "";
	if (tName.match(/\s/)) {
		ret = '"' + tName + '"';
	} else {
		ret = tName;
	}
	if (typeof(tAlias) == "undefined") {
		return ret;
	}
	
	if ( g_tableNames[tName] != g_tableAliases[tName] ) {
		if (tAlias == 1) {
			return g_tableNames[tName] + " AS " +g_tableAliases[tName];
		} else {
			return g_tableAliases[tName];
		}
	} else {
		return tName;
	}
}
