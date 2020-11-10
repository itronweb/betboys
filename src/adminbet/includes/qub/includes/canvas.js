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

var scrollbars_width = 18;
function canvas_init() {
	if (is.ie) {
		var a=18;
		var t = document.getElementById("scrollbarwidthcalculator");
		t.wrap = 'off';
		a = t.offsetHeight;
		t.wrap = 'soft';
		a -= t.offsetHeight;
		if (a) {
			scrollbars_width = a;
		}
		t.removeNode();
	}
	if (!parent.ui.loaded) {
		//return;
	}
	if (zoomFactor!=1) {
		zoomCSSRules(zoomFactor);
	}

	zDragHelper = document.getElementById("dragdrop_helper");
	zDragLink = document.getElementById("dragLink");

	if (parent.ui.is.ns) {
		document.documentElement.onmousedown = bodyMouseDown; 
		document.documentElement.onmousemove = bodyMouseMove;
		document.documentElement.onmouseup = bodyMouseUp; 
	} else {
		document.body.onmousedown = bodyMouseDown; 
		document.body.onmousemove = bodyMouseMove;
		document.body.onmouseup = bodyMouseUp; 
	}

	var relation = executeQuery("select table1_rel, table2_rel, t1id_rel, t2id_rel, type_rel, restrict_rel from qub3_relations_rel");
	var tmp;
	predefLinks = new Array();
	for (var i=0;i<relation.length;i++) {
		tmp = new Object();
		tmp.table1   = relation[i][0];
		tmp.table2   = relation[i][1];
		tmp.t1id     = relation[i][2];
		tmp.t2id     = relation[i][3];
		tmp.card1    = relation[i][4].replace(/-./, '');
		tmp.card2    = relation[i][4].replace(/.-/, ''); 
		tmp.restrict = relation[i][5];
		predefLinks.push(tmp);
	}


	current_query_name = top.ui.selectedQueryName;
	x = executeQuery("select desc_que from qub3_queries_que where name_que='"+current_query_name+"'");
	if (x!== false) {
		if (x.length > 0) {
			myQuery = unescape(x[0][0]).objdeserialize();
			myQuery.print(document.body, "diagram");
			myQuery.print("some", "table_checkboxes");
		} else {
			myQuery = new SQLQuery(current_query_name);
			if (parent.original_query_name == current_query_name && parent.one_time_add_old_tables) {
				parent.one_time_add_old_tables = false;
				if (top.ui.nxtQuery && top.ui.nxtQuery.hasJoins) {
					var startX = 30;
					nxtQuery = top.ui.nxtQuery;
					for (var i=0; i<nxtQuery.queryTables.length; i++) {
						var zTable = myQuery.new_table(nxtQuery.queryTables[i][0].table);
						zTable.addAllColumns();
						zTable.select_all = false;
						zTable.ui.x = zTable.ui.y = startX;
						startX += 40;
					}
					//	nxtQuery.queryTables;
					//	nxtQuery.orphanColumns
					//	nxtQuery.joins;
					for (var i=0; i<nxtQuery.queryTables.length; i++) {
						var zTable = nxtQuery.queryTables[i];
						for(var j=0; j<zTable.length; j++) {
							var alias = zTable[j].alias?zTable[j].alias:zTable[j].column;
							if (zTable[j].alias != "" && zTable[j].alias != zTable[j].column) {
								var newColumn = myQuery.tables.item(zTable[j].table).new_column(alias, zTable[j].column);
							}
							myQuery.tables.item(zTable[j].table).columns.item(alias).output = true;
							myQuery.tables.item(zTable[j].table).columns.item(alias).set_checked(true);
						}
					}
					myQuery.print(document.body, "diagram");
					myQuery.print("some", "table_checkboxes");
					var prevTable = null;
					for (var i=0; i<nxtQuery.joins.length; i++) {
						var jon = nxtQuery.joins[i];
						if (prevTable) {
							myQuery.tables.item(jon.t2).ui.move(myQuery.tables.item(prevTable).ui.x, myQuery.tables.item(prevTable).ui.y + myQuery.tables.item(prevTable).container.offsetHeight + 40);
						} else {
							myQuery.tables.item(jon.t2).ui.move(myQuery.tables.item(jon.t1).ui.x + myQuery.tables.item(jon.t1).container.offsetWidth + 40, myQuery.tables.item(jon.t1).ui.y - 20);
						}
						prevTable = jon.t2;
						myQuery.tables.item(jon.t1).columns.item(jon.f1).link(myQuery.tables.item(jon.t2).columns.item(jon.f2), false);
					}
				} else {
					var old_tables = parent.getOldTables();
					if (old_tables) {
						old_tables = old_tables.replace(/%2C/g, ',');
						old_tables = old_tables.replace(/%20/g, ',');
						old_tables = old_tables.replace(/\s/g, ',');
						old_tables = old_tables.split(/,/);
						var startX = 30;
						for(var i=0; i<old_tables.length; i++) {
							if (!old_tables[i]) {
								continue;
							}
							if (typeof top.ui.tablesIndexFromName[old_tables[i]]=='undefined') {
								//table not found in given connection
								continue;
							}
							var zTable = myQuery.new_table(old_tables[i]);
							zTable.addAllColumns();
							zTable.columns.item("*").set_checked(true);
							zTable.ui.x = zTable.ui.y = startX;
							startX += 20;
						}
					}
					myQuery.print(document.body, "diagram");
					myQuery.print("some", "table_checkboxes");
				}
			} else {
				myQuery.print(document.body, "diagram");
				myQuery.print("some", "table_checkboxes");
			}
		}
		top.ui.myQuery = myQuery;
	}
	undo.addUndo("start");
}

var whereCond = new Array();
var selectedCols = new Array();

function Undo() {
	this.storage = [];
	this.storage[0] = {s:"",d:""};
	this.cursor = 0;
	this.locked = false;

	this.addUndo = function(descriptor) {
		if (this.locked) {
			return;
		}
		var sQuery = top.canvas.myQuery.serialize();
		if (this.cursor>0 && sQuery==this.storage[this.cursor].s) {
			//zdbg("Do not add undo.");
			return;
		}
		var tmp = [];
		for(var i=0; i<=this.cursor+1; i++) {
			tmp[i] = this.storage[i];
		}
		this.storage = null;
		this.storage = tmp;
		this.storage[this.cursor+1] = {s:sQuery, d:descriptor};
		this.cursor = this.storage.length-1;
		//zdbg("Add undo ["+descriptor+"]:" + this.cursor +":"+this.storage.length, true);
	}
	this.undo = function() {
		this.doit(this.cursor-1);
		//zdbg("Undo ["+this.storage[this.cursor].d+"]"+this.cursor +":"+this.storage.length, true);
	}
	this.redo = function() {
		this.doit(this.cursor+1);
		//zdbg("Redo ["+this.storage[this.cursor].d+"]"+this.cursor +":"+this.storage.length, true);
	}
	this.doit = function(i) {
		if (i<=0 || i>=this.storage.length) {
			return;
		}
		this.cursor = i;
		if (this.timeout) {
			window.clearTimeout(this.timeout);
		}
		this.timeout = window.setTimeout("late_undo_doit()", 140);
	}

	this.lock = function () {
		this.locked = true;
	}
	this.unlock = function () {
		this.locked = false;
	}
}

function late_undo_doit () {
	undo.timeout = null;
	var sQuery = undo.storage[undo.cursor];
	top.canvas.myQuery.container.parentNode.removeChild(top.canvas.myQuery.container);
	top.canvas.myQuery = sQuery.s.objdeserialize();
	for(var i=0;i<top.canvas.myQuery.tables.length; i++) {
		top.canvas.myQuery.tables.item(i).ui.selected = false;
	}
	top.canvas.myQuery.print(top.canvas.document.body, "diagram");
	top.canvas.myQuery.print("some", "table_checkboxes");
	top.ui.invalidate(true, true, 'sqlresults,sqlcolumns');
}
var undo = new Undo();
//will be unlocked by the qub.js after everything is loaded
undo.lock();