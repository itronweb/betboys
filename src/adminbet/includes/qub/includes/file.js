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
function checkQueryExists(name) {
	var ret = false;
	name = name.toLowerCase();
	for (var i=0; i<top.ui.all_queries.length ;i++) {
		if (name == top.ui.all_queries[i].toLowerCase()) {
			ret = true;
			break;
		}
	}
	return ret;
}
var suggestedQueryName = "";
var orig_name_que = "";
function saveAs(){
	orig_name_que = top.ui.selectedQueryName;
	openIT("dlgSave.html", 350, 140, 120, 120, "_dlgConfirmSave", "Edit Relation");
}

function saveitdada(suggestedQueryName, goto_where) {
	queryName = suggestedQueryName;

	var ok;
	showPleaseWait('Please wait while saving', '160px', '');
	var old_query_name = ui.selectedQueryName;
	ui.selectedQueryName = queryName;
	ok = save(1);
	if (ok) {
		top.ui.invalidate(null, false);
		deselectUIQuery(ui.all_queriesIndexFromName[orig_name_que]);

		if (top.ui.is_new_query && old_query_name != queryName) {
			ui.all_queries.splice(ui.selectedQueryIndex, 1);
			indexArray("all_queries");
		}
		top.ui.is_new_query = false;
		if (goto_where == 'replace_query_name') {
			var to_idx = ui.all_queriesIndexFromName[queryName];
			selectUIQuery(ui.all_queriesIndexFromName[queryName]); 
			ui.selectedQueryIndex = ui.all_queriesIndexFromName[queryName];
		} else if (goto_where == 'new_query_name') {
			ui.all_queries[ui.all_queries.length] = queryName;
			ui.all_queries.sort();
			indexArray("all_queries");
			uiQueriesRepaint();
			ui.selectedQueryIndex = ui.all_queriesIndexFromName[queryName];
			selectQuery(queryName);
		}
	} else {
		ui.selectedQueryName = old_query_name;
	}
	hidePleaseWait(true);
}

function save(load){
	if (top.ui.myQuery.tables.length == 0) {
		alert(locales['Cannot save empty query.']);
		hidePleaseWait(true);
		return false;
	}

	var loca_name_que = "";

	top.mo.moving = 0;
	if (load == 0) {
		saveIt = confirm(locales["Save current Query?"]);
	} else {
		saveIt = true;
	}
	if (saveIt) {
		if (top.ui.selectedQueryIndex!=null) {
			loca_name_que = ui.selectedQueryName;
		} else {
			loca_name_que = prompt("Please enter a name for this query:", ui.selectedQueryName);
		}
		if (loca_name_que) {
			loca_name_que = loca_name_que.replace(/[^\w]/g, "");
		}
		if (loca_name_que) {
			window.status = 'Saving...'; 
			var desc_que = "";
			canvas.unSelectTables(true);	
			var empty_que = true;
			
			canvas.genSql();
			temp = "";
			var rels = canvas.myQuery.relations;
			for(i=0;i<rels.length;i++){
				temp += rels.item(i).table1+"|"+rels.item(i).table2+"|"+rels.item(i).field1+"|"+rels.item(i).field2+"|"+rels.item(i).card1+"-"+rels.item(i).card2+"|"+rels.item(i).restrict+"\n";
			}
			var relations_rel=temp;
			ui.selectedQueryName = loca_name_que;
			var query_que = canvas.sqlQuery;
			
			desc_que = escape(desc_que);
			query_que = escape(query_que).replace(/\+/g, "%2B");

			//new method
			canvas.myQuery.reindex_columns();
			desc_que = escape(canvas.myQuery.serialize());
			empty_que = false;
			if (!empty_que) {
				clearSQLErrors();
				var rs, x, sql;
				sql = "select count(*) as numrows from qub3_queries_que where name_que = '" + loca_name_que + "'";
				x = executeQuery(sql);
				if (x !== false && x.length > 0 && x[0][0] > 0) {
					sql = "update qub3_queries_que set desc_que = '" + desc_que + "', query_que = '" + query_que + "', version_que = '1.0' where name_que = '" + loca_name_que + "'";
				} else {
					sql = "insert into qub3_queries_que (name_que, desc_que, query_que, tables_que, version_que) values ('" + loca_name_que + "', '" + desc_que + "', '" + query_que + "', ' ', '1.2')";
				}
				x = executeQuery(sql);
				
				//relations:
				var qdel = "";
				var qins = [];
				
				for (var i=0 ; i<canvas.myQuery.relations.length; i++) {
					var rel = canvas.myQuery.relations.item(i);
					qdel += (qdel==""?"delete from qub3_relations_rel where ":" OR ");
					var table1 = canvas.myQuery.tables.item(rel.table1).table_name;
					var table2 = canvas.myQuery.tables.item(rel.table2).table_name;
					qdel += " (table1_rel = '" + table1 + "' AND table2_rel = '" + table2 + "' or table2_rel = '" + table1 + "' AND table1_rel = '" + table2 + "')";
					if ( typeof(qins[table1+table2])=="undefined" && typeof(qins[table2+table1])=="undefined") {
						qins[table1+table2] = "insert into qub3_relations_rel (table1_rel, table2_rel, t1id_rel, t2id_rel, type_rel, restrict_rel) values ('" + table1 + "', '" + table2 + "', '" + rel.field1 + "', '" + rel.field2 + "', '" + rel.card1+"-"+rel.card2 + "', " + (rel.restrict=='yes'?1:0) + ")";
					}
				}
				if (qdel != '') {
					x = executeQuery(qdel);
				}
				for (var qid in qins) {
					if ( !qins.hasOwnProperty(qid) ) {
						continue;
					}
					x = executeQuery(qins[qid]);
				}
				if (getSQLErrors().length == 0) {
					window.status = locales["Saved"] + ": " + loca_name_que;
					top.ui.invalidate(null, false);
					top.ui.is_new_query = false;
				} else {
					//we had some problems
					clearSQLErrors();
				}
			} else {
				alert(locales["Cannot save empty query."]);
			}
			hidePleaseWait();
			return true;
		}
	} else {
		//canvas.window.location="canvas.html";
		return true;
	}
	return false;
}

function deleteDBQuery(loca_name_que) {
	temp = "";
	var rels = canvas.myQuery.relations;
	for(i=0;i<rels.length;i++){
		temp += rels.item(i).table1+"|"+rels.item(i).table2+"|"+rels.item(i).field1+"|"+rels.item(i).field2+"|"+rels.item(i).card1+"-"+rels.item(i).card2+"|"+rels.item(i).restrict+"\n";
	}
	var relations_rel=temp;

	var rs, x=false, sql;
	sql = "delete from qub3_queries_que where name_que = '" + loca_name_que + "'";
	x = executeQuery(sql);

	relTables = relations_rel.split("\n");
	var delArr = new Array();
	sql = "delete from qub3_relations_rel where";
	for(i=0; i<relTables.length-1; i++) {
		rows = relTables[i].split("|");
		if (!delArr[rows[0] + rows[1]]) {
			sql += (sql=="delete from qub3_relations_rel where"?"":" or") + " (table1_rel = '" + rows[0] + "' AND table2_rel = '" + rows[1] + "' or table2_rel = '" + rows[0] + "' AND table1_rel = '" + rows[1] + "')";
			delArr[rows[0] + rows[1]] = 1;
			delArr[rows[1] + rows[0]] = 1;
		}
	}
	if (sql!="delete from qub3_relations_rel where") {
		x = executeQuery(sql);
	}
	if (x !== false) {
		window.status = locales["Deleted"] + ": " + loca_name_que;
		return true;
	} else {
		return false;
	}
	
}
function deleteQuery (load) {
	var loca_name_que = "";
	window.status = 'Deleting...'; 
	if(canvas.zoomFactor != 1) {
		tmp = canvas.zoomFactor;
		canvas.zoomFactor = 1;
		canvas.zoom(1/tmp);
	}
	
	top.mo.moving = 0;
	if (load == 0) {
		saveIt = confirm(locales["Delete current Query?"]);
	} else {
		saveIt = true;
	}
	if (saveIt) {
		if (window.ui.selectedQueryIndex!=null) {
			loca_name_que = ui.selectedQueryName;
		} else {
			//nothing selected, nothing to delete
			return false;
		}
		
		if (loca_name_que) {
			loca_name_que = loca_name_que.replace(/[^\w]/g, "");
		}
		if (loca_name_que) {
			var desc_que = "";
			canvas.unSelectTables(true);	
			deleteDBQuery(loca_name_que);
			return true;
		}
	} else {
		canvas.window.location="canvas.html";
		return true;
	}
	return false;
}
function openQuery(){
 	canvas.openIT("open.html", 200, 100, 140, 140, "_open", "Open Query");
}

function closeQUB(){
	if (window.navigator.appName.match(/Microsoft/)) {
		window.close();
	} else {
		//alert("The current application cannot be closed.\n\nPlease use the window close button to close the current application.");
		self.close();
	}
}

function printIt(){
	if (top.ui.myQuery.tables.length>0) {
	 	canvas.openIT("dlgPrint.html", 780, 640, 100, 100, "_print", "Print Diagram");
	}
	//alert(locales["Not yet available."]);
	//top.canvas.print();
}
