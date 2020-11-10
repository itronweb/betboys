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

 var tableN = 0;

function settings() {
 	openIT("dlgSettings.html", 400, 190, 130, 130, "_settings", "Settings");
}

function tableHasField(table_name, field_name) {
	return myQuery.tables.item(table_name).columns.item(field_name);
}

function addRelatedTables(thing) {
	if (!thing) {
		window.setTimeout("addRelatedTables(1)",1);
		return;
	}
	var tmp = currentDiv[0];
	var t_alias = tmp.id;
	if (!tmp) {
		return;
	}
	var z_table = myQuery.tables.item(t_alias);
	var t_name = z_table.table_name;
	var cool_z1 = cool_z2 = '';
	var do_refresh = false;
	for(i=0; i<predefLinks.length; i++) {
		if(predefLinks[i].table1 == t_name && predefLinks[i].table2 != t_name) {
			do_refresh = true;
			n_table = newTable1(predefLinks[i].table2, nextTableAlias(predefLinks[i].table2));
			n_table.print("whatever", 'diagram');
			n_table.columns.item("*").set_checked(true);
			z_table.columns.item(predefLinks[i].t1id).link(n_table.columns.item(predefLinks[i].t2id));
		}

		if(predefLinks[i].table2 == t_name && predefLinks[i].table1 != t_name) {
			do_refresh = true;
			n_table = newTable1(predefLinks[i].table1, nextTableAlias(predefLinks[i].table1));
			n_table.print("whatever", 'diagram');
			n_table.columns.item("*").set_checked(true);
			n_table.columns.item(predefLinks[i].t1id).link(z_table.columns.item(predefLinks[i].t2id));
		}
	}

	if (do_refresh) {
		top.canvas.undo.addUndo("Add related tables");
		top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
	}
}

function nextTableAlias(tName) {
	var z_table = myQuery.tables.item(tName);
	if (typeof z_table=="undefined") {
		return tName;
	}
	tName = z_table.table_name;
	var suf = '';
	var n_table_suf = tName + suf;
	while (document.getElementById(n_table_suf)) {
		suf = suf === ''?0:suf+1;
		n_table_suf = tName + '_' + suf;
	}
	return n_table_suf;
}

function duplicateTable(tName) {
	var zTable = myQuery.tables.item(tName);
	var ret = newTable(zTable.table_name, nextTableAlias(zTable.table_name), false);
	if(ret) {
		top.canvas.undo.addUndo("Duplicate table");
	}
	return ret;
}

function changeTableAlias(tName) {
	openIT("dlgEditAlias.html", 330, 150, 130, 130, "_table", "Edit Table Alias");
}
function newTable1(tName, tAliasName) {
	if (typeof myQuery=="undefined") {
		top.myQuery = myQuery = new SQLQuery("myQuery");
		myQuery.print(top.canvas.document.body, "diagram");
	}
	if (typeof(tAliasName) == "undefined") {
		tAliasName = tName;
	}
	var zTable = myQuery.new_table(tName, tAliasName);

	zTable.addAllColumns();
	return zTable;
}

function newTable(tName, tAliasName, addPredefLinks){
	if (typeof(tAliasName) == "undefined") {
		tAliasName = tName;
	}
	if (document.getElementById(tAliasName)) {
		// if the table is already added
		//duplicate it?
		return false;
	}
	if (!newTable1(tName, tAliasName)) {
		// if the table was dropped delete all relations
		var i;
 		for(i=0;i<predefLinks.length;i++){
 			if(predefLinks[i].table1 == tAliasName || predefLinks[i].table2 == tAliasName){
				executeQuery("delete from qub3_relations_rel where table1_rel='"+tAliasName+"' or table2_rel='"+tAliasName+"'");
				break;
 			}
 		}
		return false;
	}

	myQuery.tables.item(tAliasName).print("whatever", 'diagram');
	myQuery.tables.item(tAliasName).columns.item("*").set_checked(true);

 	tableN++;
	tmp = myQuery.tables.item(tAliasName).container;

 	tmp.style.left = tableN*30;
 	tmp.style.top = tableN*10+20;
	myQuery.tables.item(tAliasName).ui.x = tableN*30;
	myQuery.tables.item(tAliasName).ui.y = tableN*10+20;

	if (typeof addPredefLinks=="undefined") {
		addPredefLinks = true;
	}

	if (addPredefLinks && predefLinks.length) {
		for (i=0;i<predefLinks.length;i++) {
			if(predefLinks[i].table1 == tAliasName && predefLinks[i].table2 != tAliasName && myQuery.tables.item(predefLinks[i].table2)) {
				var ret = myQuery.tables.item(predefLinks[i].table1).columns.item(predefLinks[i].t1id).link(
					myQuery.tables.item(predefLinks[i].table2).columns.item(predefLinks[i].t2id)
				);
			} 
			if (predefLinks[i].table2 == tAliasName && predefLinks[i].table1 != tAliasName && myQuery.tables.item(predefLinks[i].table1)) {
				var ret = myQuery.tables.item(predefLinks[i].table1).columns.item(predefLinks[i].t1id).link(
					myQuery.tables.item(predefLinks[i].table2).columns.item(predefLinks[i].t2id)
				);
			}
		}
	}

	top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
	return myQuery.tables.item(tAliasName);
}

function readInfoFromTable(tbl) {

}
function unSelectTables(b){
	if (b) {
		if(!top.mo.moving){
			while(currentDiv.length) {
				temp = currentDiv.pop();
				myQuery.tables.item(temp.id).deselect();
			}
		}
	} else {
		setTimeout("unSelectTables(1);", 100)
	}
}

function syncTable() {
	if (currentDiv.length != 1) {
		alert("Please select one table.");
	} else {
		var d;
		var d = myQuery.tables.item(top.mo.selectedTable).table_name;
		var a = myQuery.tables.item(top.mo.selectedTable).name;
		window.setTimeout("lateSync('" + d + "', '" + a + "')", 1);
	}
}
function lateSync(d, a) {
	top.canvas.undo.lock();
	var currentTable = myQuery.tables.item(top.mo.selectedTable);
	currentTable.deselect();

	var savedString = currentTable.serialize() + "\r\nret = tmp;";
	var savedTable = savedString.objdeserialize(false);

	var savedTableRelations = [];
	for(var i=0; i<myQuery.relations.length; i++) {
		if (myQuery.relations.item(i).table1 != savedTable.name && myQuery.relations.item(i).table2 != savedTable.name) {
			continue;
		}
		srel = myQuery.relations.item(i).serialize() + "\r\nret = tmp;";
		srel = srel.objdeserialize(false);
		srel.query = myQuery;
		savedTableRelations.push(srel);
		myQuery.relations.item(i).remove();
		i--;
	}

	currentTable.remove();
	var freshTable = newTable1(d, a);

	for (var i=0; i<freshTable.columns.length; i++) {
		var colName = freshTable.columns.item(i).name;
		if(typeof(savedTable.columns.item(colName)) != "undefined") {
			freshTable.columns.item(i).copy(savedTable.columns.item(colName));
		}
	}

	for (var i=0; i<savedTable.columns.length; i++) {
		var colName = savedTable.columns.item(i).name;
		var realName = savedTable.columns.item(i).column_name;
		if(colName!=realName && typeof(freshTable.columns.item(realName)) != "undefined") {
			var newClmn = freshTable.new_column(colName, realName);
			newClmn.copy(savedTable.columns.item(colName));
		}
	}
	
	freshTable.ui = savedTable.ui;
	freshTable.print("to", "diagram");

	for(var i=0;i<savedTableRelations.length; i++) {
		var rel = savedTableRelations[i];
		if(
			rel.table1==savedTable.name && typeof(savedTable.columns.item(rel.field1)) != "undefined"
			 ||
			rel.table2==savedTable.name && typeof(savedTable.columns.item(rel.field2)) != "undefined"
		) {
				var srel = savedTableRelations[i]
				myQuery.relations.add(srel);
				srel.ui = {relLeft:0, relTop1:0, relTop2:0};
				srel.repaint();
			}
	}
	freshTable.select();
	top.canvas.undo.unlock();

	top.canvas.undo.addUndo("Sync table");
	top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
}

function delTable(name, refreshQuery, delete_aliases) {
	if (typeof refreshQuery == "undefined") {
		refreshQuery = true;
	}
	if (typeof delete_aliases == "undefined") {
		delete_aliases = true;
	}
	var old_tables = [], new_tables = [];
	Array_each(myQuery.tables.items, function(tbl) {
		Array_push(old_tables, tbl.table_name);
	});

	var tmp = "table \"" + name + "\"";
	if (refreshQuery && !confirm(sprintf(locales["Are you sure you want to remove"], tmp))) {
		return false;
	}
	for(var i=0; i < currentDiv.length; i++) {
		if(currentDiv[i].id == name) {
			currentDiv.splice(i, 1);
			break;
		}
	}

	if (delete_aliases) {
		//delete all sqltables having the wanted table_name, no matter the alias
		for (var i=0; i<myQuery.tables.length; i++) {
			if (myQuery.tables.item(i).table_name == name || myQuery.tables.item(i).name == name) {
				myQuery.tables.item(i).deselect();
				myQuery.tables.item(i).remove();
				i--;
			}
		}
	} else {
		var sqlTable = myQuery.tables.item(name);
		sqlTable.deselect();
		sqlTable.remove();
	}
	if (refreshQuery) {
		top.ui.invalidate(true, true, 'sqlquery');
	}

	Array_each(myQuery.tables.items, function(tbl) {
		Array_push(new_tables, tbl.table_name);
	});
	Array_each(old_tables, function(tbl) {
		if (Array_indexOf(new_tables, tbl) < 0 || new_tables.length == 0) {
			top.deselectQueryTable(tbl);
		}
	});
	
	return true;
}

 function delTables(force){
	if (currentDiv.length) {
		var tmp = "";
		var added = new Array();
		var old_tables = [], new_tables = [];
		Array_each(myQuery.tables.items, function(tbl) {
			Array_push(old_tables, tbl.table_name);
		});

		for (i=0;i<currentDiv.length;i++) {
			if (added[currentDiv[i].id] == 1) {
				continue;
			}
			added[currentDiv[i].id] = 1;
			if (i!=0) {
				if(i!=currentDiv.length-1){
					tmp += ", ";
				} else {
					tmp += " and ";
				}
			}
			tmp += "\"" + currentDiv[i].id + "\"";
		}
		if (currentDiv.length>1) {
			tmp = "tables " + tmp;
		} else {
			tmp = "table " + tmp;
		}
		if (force || confirm(sprintf(locales["Are you sure you want to remove"], tmp))){
			while (currentDiv.length) {
				temp = currentDiv.pop();
				delTable(temp.id, false, false);
			}
			top.canvas.undo.addUndo("Remove tables.");
			top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
		}
		Array_each(myQuery.tables.items, function(tbl) {
			Array_push(new_tables, tbl.table_name);
		});
		Array_each(old_tables, function(tbl) {
			if (Array_indexOf(new_tables, tbl) < 0) {
				top.deselectQueryTable(tbl);
			}
		});
	} else {
		alert(locales["No tables are selected."]);
	}
}
